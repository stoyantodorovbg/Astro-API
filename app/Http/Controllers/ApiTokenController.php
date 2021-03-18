<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\GetApiTokenRequest;

class ApiTokenController extends Controller
{
    /**
     * Refresh and Get Api Token
     *
     * @param GetApiTokenRequest $request
     * @return array
     */
    public function getApiToken(GetApiTokenRequest $request)
    {
        Redis::set('refreshing_api_token_' . $request->email, true);
        Redis::set('api_user_token_' . $request->email, Str::random(50));
        Redis::set('refreshing_api_token_' . $request->email, false);

        return ['api_token' => Redis::get('api_user_token_' . $request->email)];
    }
}
