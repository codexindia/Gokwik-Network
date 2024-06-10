<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSecretToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');
        if ($request->header('secret') == 'hellothisisocdexindia') {
             return $next($request);
        }
        return response()->json([
                'status' => false,
                'message' => 'Invalid Secret Token',
         ]);
       
    }
}
