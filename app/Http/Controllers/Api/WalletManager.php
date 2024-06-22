<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Models\CoinsTransaction;
use App\Models\CoinTransfer;
use Illuminate\Support\Facades\DB;

class WalletManager extends Controller
{
    public function getTransaction(Request $request)
    {
        $record = CoinsTransaction::select(['coin', 'transaction_type', 'description', 'transaction_id', 'status', 'created_at'])
            ->where('user_id', $request->user()->id)
            ->orderBy('id','desc')
            ->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $record,
            'message' => 'Retreive Success'
        ]);
    }
    public function getNameByUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|min:4'
        ]);
      
        $user = User::select(['name', 'username', 'profile_pic'])->where('username', $request->username)->first();
        if($user == null)
        {
            return response()->json([
                'status' => false,
               
                'message' => 'Username Is Invalid'
            ]);
        }
        return response()->json([
            'status' => true,
            'data' => $user,
            'message' => 'Retrieve Success'
        ]);
    }
    public function sendCoin(Request $request)
    {
        $request->validate([
            'username' => 'required|min:4',
            'coins' => 'required|numeric|gt:0|lt:2000'
        ]);
        $check_username = User::where('username', $request->username)->first();
        if ($request->user()->coin < $request->coins) {
            return response()->json([
                'status' => false,
                'message' => "You Do Not Have Enough Balance"
            ]);
        }
        if ($request->user()->username == $request->username || $check_username == null) {
            return response()->json([
                'status' => false,
                'message' => "Coin Can't Be Transferred Due To Invalid Username"
            ]);
        }
        DB::beginTransaction();
        try {
            $new = new CoinTransfer;
            $new->user_id = $request->user()->id;
            $new->transfer_to = $check_username->id;
            $new->status = 'success';
            $new->trx_id = coin_action($request->user()->id, $request->coins, 'debit', 'Coin Transferred To @' . $request->username, [
                'ip' => $request->ip()
            ]);
            $new->meta = json_encode([
                'ip' => $request->ip()
            ]);
            $new->save();
            coin_action($check_username->id, $request->coins, 'credit', 'Coin Received From @' . $request->user()->username);
            DB::commit();
            return response()->json([
                'status' => true,
                'coins' => $request->coins,
                'trx' =>  $new->trx_id,
                'message' => 'Coin Transferred SuccessFully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            $trx = 'GKC' . $request->user()->id . time() . rand('10', '99');
            $transaction = new CoinsTransaction;
            $transaction->user_id = $request->user()->id;
            $transaction->coin = $request->coins;
            $transaction->transaction_type = 'debit';
            $transaction->description = 'Money Could Not Ne Transferred. Something Went Wrong';
            $transaction->transaction_id = $trx;
            $transaction->status = 'failed';
            $transaction->meta = json_encode([
                'ip' => $request->ip()
            ]);
            $transaction->save();


            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong'
            ]);
        }
    }
}
