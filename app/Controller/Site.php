<?php

namespace Controller;

use Src\View;
use Src\Request;
use Model\Staff;

class Site
{
    public function hello(): string
    {
        return new View('site.hello', ['message' => 'Научный отдел']);
    }

    public function signup(Request $request): string
    {
        if ($request->method === 'POST') {
            Staff::create($request->all());
            app()->route->redirect('/login');
        }
        return new View('site.signup');
    }

    public function login(Request $request): string
    {
        if ($request->method === 'GET') {
            return new View('site.login');
        }
        if (app()->auth::attempt($request->all())) {
            app()->route->redirect('/hello');
        }
        return new View('site.login', ['message' => 'Ошибка входа']);
    }

    public function logout(): void
    {
        app()->auth::logout();
        app()->route->redirect('/login');
    }
}