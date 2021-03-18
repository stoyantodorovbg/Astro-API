<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ((!$userApiToken = Redis::get('api_user_token_' . $request->email)) ||
            $userApiToken !== $request->api_token
        ) {
            logger($userApiToken);
            logger($request->api_token);
            logger('wrong token!!!!!!!!!!!!!');
            abort(403);
        }

        return $next($request);
    }
}
