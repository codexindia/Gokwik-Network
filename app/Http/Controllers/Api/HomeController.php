<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ReferData;
use App\Models\MiningSession;
use Illuminate\Support\Facades\DB;
use App\Models\PopupBanner;
class HomeController extends Controller
{
    public function Statics(Request $request)
    {
        $user_id = $request->user()->id;
        $data = array();
        $data['active_miners'] = ReferData::where('user_id', $user_id);


        $total_remote_earning = 0;
   

        $total_remote_earning = ReferData::where('user_id', $user_id)->sum('coins_earn');



        return response()->json([
            'status' => true,
            'valuation' => array(
                'currency' => 'USD',
                'rate' => get_setting('coin_valuation'),
            ),
            'active_miners' => $data['active_miners']->count(),
            'total_miners' => $data['active_miners']->count(),
            'total_live_mining' =>MiningSession::sum('coin'),
            'total_remote_mining' => $total_remote_earning,

        ]);
    }
    public function popup_banner(Request $request)
    {
        $data = PopupBanner::first();
        if(!$data->visibility)
        return response()->json([
           'status' => false,
       ]);
       else
        return response()->json([
            'status' => true,
            'button_text' => $data->button_text,
            'banner_image' => url('/storage/'.$data->image),
            'action_link' => $data->action_link
            
        ]);

    }
}
