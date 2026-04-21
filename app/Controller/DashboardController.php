<?php

namespace Controller;

use Src\View;

class DashboardController
{
    public function home(): void
    {
        if (app()->auth::check()) {
            app()->route->redirect('/dashboard');
        }
        app()->route->redirect('/login');
    }

    public function dashboard(): string
    {
        $user = app()->auth::user();
        $isAdmin = ($user->role_id == 1);
        
        return new View('site.dashboard', [
            'isAdmin' => $isAdmin,
            'user' => $user
        ]);
    }
}