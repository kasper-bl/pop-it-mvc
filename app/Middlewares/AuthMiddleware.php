<?php

namespace Middlewares;

use Src\Request;

class AuthMiddleware
{
    public function handle(Request $request)
    {
        if (!app()->auth::check()) {
            app()->route->redirect('/login');
        }
        return $request;
    }
}