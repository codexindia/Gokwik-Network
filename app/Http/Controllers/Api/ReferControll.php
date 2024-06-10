<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ReferData;

class ReferControll extends Controller
{
    public function claim(Request $request)
    {
        $request->validate([
            'refer_code' => 'required|exists:users,refer_code'
        ]);
        $user = User::findOrFail($request->user()->id);
        if ($user->referred_by != null || $user->referred_by == "skiped") {
            return response()->json([
                'status' => false,
                'message' => 'Referred Status Can not Update'
            ]);
        }
        $sourceuser = User::where('refer_code', $request->refer_code)->first(['id','country_code','phone_number']);
        ReferData::create([
            'user_id' => $sourceuser->id,
            'referred_to' => $user->id,
            'coins_earn' => get_setting('referral_coin'),
        ]);
        $user->update([
            'referred_by' => $request->refer_code,
        ]);
        sendpush($sourceuser->country_code.$sourceuser->phone_number,'@'.$user->username.' Just Joining Through Your Refer Code');
        return response()->json([
            'status' => true,
            'message' => 'Referred Status Updated'
        ]);
    }
    public function skip(Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        $user->update([
            'referred_by' => 'skiped',
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Referred Status Updated'
        ]);
    }
    public function get_referred_members(Request $request)
    {
        $user_id =  $request->user()->id;
        $members = ReferData::where('user_id', $user_id)->select(['user_id','coins_earn','referred_to'])->with('Profile:id,name,profile_pic,username')->orderBy('id', 'desc')->paginate(10);
        $coins_earn = ReferData::where('user_id', $user_id)->sum('coins_earn');
        return response()->json([
            'status' => true,
            'referred_bonus' => get_setting('referral_coin'),
            'coins_earned' => $coins_earn,
            'list' => $members
        ]);
    }
}
