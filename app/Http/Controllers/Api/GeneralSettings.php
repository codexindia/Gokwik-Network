<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeneralSettings extends Controller
{
    public function check_version(Request $request)
    {
        if (get_setting('force_update')) {
            return response()->json([
                'status' => true,
                'force_update' => true,
                'version_code' => get_setting('version_code'),
                'store_link' => "https://play.google.com/store/apps/details?id=com.crypto.miner.masth",
                'custom_link' => null
            ]);
        }else{
            return response()->json([
                'status' => true,
                'force_update' => false,
                'version_code' => get_setting('version_code'),
                'store_link' => "https://play.google.com/store/apps/details?id=com.crypto.miner.masth",
                'custom_link' => null
            ]);
        }
    }
    public function check_maintenance(Request $request)
    {
        if (get_setting('maintenance_mode')) {
            return response()->json([
                'status' => true,
            ]);
        }
        return response()->json([
            'status' => false,
        ]);
    }
}
