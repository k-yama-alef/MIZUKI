<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * 認証されていないときにユーザーがリダイレクトされるパスを取得します。
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        info('redirectTo 1');
        if (! $request->expectsJson()) {
            // info('redirectTo 2');
            // return route('login');
        }
    }
}
