<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\BlockedUser;

class CheckBan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $users = BlockedUser::where('user_id', $request->user()->id)->first();
        if ($users != null) {
            return response()->json([
                'status' => false,
                'blocked' => true,
                'message' => $users->reason,
            ]);
        }
        return $next($request);
    }
}
