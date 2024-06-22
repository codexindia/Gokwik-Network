<?php

use App\Models\Settings;
use App\Models\CoinsTransaction;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Berkayk\OneSignal\OneSignalFacade as OneSignal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

if (!function_exists('get_setting')) {
    function get_setting($key, $group = 'general')
    {
        $result = Settings::where('name', $key)->where('group', $group)->first('payload');
        if ($result != null) {
            return str_replace('"', '', $result->payload);;
        } else {
            return 0;
        }
    }
}
if (!function_exists('coin_action')) {
    function coin_action(int $user_id, float $coins, $type = "debit", $description = null, $meta = [])
    {

        $user = User::findOrFail($user_id);
        if ($user)
        $trx = 'GKC' . $user_id . time() . rand('10', '99');
            $transaction = new CoinsTransaction;
        $transaction->user_id = $user_id;
        $transaction->coin = $coins;
        $transaction->transaction_type = $type;
        $transaction->description = $description;
        $transaction->transaction_id = $trx;
        $transaction->status = 'success';
        $transaction->meta = json_encode($meta);
        if ($transaction->save()) {
            if ($type == "credit") {
                if ($user->increment('coin', $coins)) {

                    return $trx;
                } else {
                    return false;
                }
            } else {
                if ($user->decrement('coin', $coins)) {

                    return true;
                } else {
                    return false;
                }
            }
        }
        return false;
    }
    function sendpush($user, $text, $heading = null, $params = [])
    {
       
       
       
        if ($user == null) {
            OneSignal::addParams($params)->sendNotificationToAll(
                $text,
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null,
                $heading
            );
        } else {
            $notify['title'] = 'From Masth';
            $notify['subtitle'] = $text;
            $user->notify(new GeneralNotification($notify));
            OneSignal::addParams($params)->sendNotificationToExternalUser(
                $text,
                $user->country_code . $user->phone_number,
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null
            );
        }
    }
}
