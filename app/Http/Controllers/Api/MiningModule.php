<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MiningSession;

class MiningModule extends Controller
{

    public function checkMiningStatus(Request $request)
    {

        $mining = MiningSession::where([
            'user_id' => $request->user()->id,
            'status' => 'running'
        ])->get();
        if ($mining->count() > 0) {
            $data = $mining->first();
            $data['current_time'] = Carbon::now();
            return response()->json([
                'status' => false,
                'mining_function' => false,
                'mining_data' => $data,
                'message' => 'An Active Mining Session is already Running'
            ]);
        }
        if (!get_setting('mining_function')) {
            return response()->json([
                'status' => false,
                'mining_function' => false,
                'message' => "mining Currently Turned Off"
            ]);
        }
        return response()->json([
            'status' => true,
            'mining_function' => true,
            'message' => 'No mining Session is currently Running'
        ]);
    }
    public function startMining(Request $request)
    {
        if (!get_setting('mining_function')) {
            return response()->json([
                'status' => false,
                'mining_function' => false,
                'message' => "mining Currently Turned Off"
            ]);
        }
        $mining = MiningSession::where([
            'user_id' => $request->user()->id,
            'status' => 'running'
        ])->get();
        if ($mining->count() > 0) {
            return response()->json([
                'status' => false,
                'mining_function' => false,
                'message' => 'An Active Mining Session is already Running'
            ]);
        }
        $new = new MiningSession;
        $new->session_id = $request->user()->id . time() . rand('10', '99');
        $new->user_id = $request->user()->id;
        $new->start_time = Carbon::now();
        $new->end_time = Carbon::now()->addHours(3);
        $new->coin = 3;
        $new->save();
        return response()->json([
            'status' => true,
            'message' => 'Mining Session Submit SuccessFully'
        ]);
    }
}
