<?php

namespace Controller;

use Src\View;
use Src\Request;

class AuthController
{
    public function hello(): string
    {
        return new View('site.hello', ['message' => 'Научный отдел']);
    }

    public function login(Request $request): string
    {
        if ($request->method === 'GET') {
            return new View('site.login');
        }
        if (app()->auth::attempt($request->all())) {
            app()->route->redirect('/dashboard');
        }
        return new View('site.login', ['message' => 'Ошибка входа']);
    }

    public function logout(): void
    {
        app()->auth::logout();
        app()->route->redirect('/login');
    }
}