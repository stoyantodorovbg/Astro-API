<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class GetApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $secretApiToken = Redis::get('api_user_secret_token_' . $request->email);

        if ($secretApiToken === null) {
            $secretApiToken = User::where('email', $request->email)->first()->api_secret_token;
            Redis::set('api_user_secret_token_' . $request->email, $secretApiToken);
        }

        if ($secretApiToken !== $request->api_secret_token) {
            abort(403);
        }

        return $next($request);
    }
}
