<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CoinsTransaction;

class WalletManager extends Controller
{
    public function getTransaction(Request $request)
    {
        $record = CoinsTransaction::
        select(['coin','transaction_type','description','transaction_id','status','created_at'])
        ->where('user_id', $request->user()->id)
        ->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $record,
            'message' => 'Retreive Success'
        ]);
    }
}
