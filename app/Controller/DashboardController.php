<?php

namespace Controller;

use Src\View;

class DashboardController
{
    public function index(): string
    {
        $user = app()->auth::user();
        $isAdmin = ($user->id_role == 1);
        
        return new View('site.dashboard', [
            'isAdmin' => $isAdmin,
            'user' => $user
        ]);
    }
}