<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationManager extends Controller
{
    public function getNotification(Request $request)
    {
        $user = User::find($request->user()->id);

        $data = [];

        foreach ($user->unreadNotifications as $notification) {

            $data['notifications'][] =  [
                'id' => $notification->id,
                'title' => $notification->data['title'],
                'subtitle' => $notification->data['subtitle'],
            ];
        }
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'Notification Retrieve SuccessFully',
        ]);
    }
    public function markRead(Request $request)
    {

        // $user = User::find($request->user()->id);
        // $user->unreadNotifications->markAsRead();
        return response()->json([
            'status' => true,
        ]);
    }
}
