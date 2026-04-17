<?php

namespace Middlewares;

use Src\Request;

class RoleMiddleware
{
    public function handle(Request $request, string $role): Request
    {
        $user = app()->auth::user();
        
        if (!$user) {
            app()->route->redirect('/login');
        }
        
        $allowed = false;
        
        switch ($role) {
            case 'admin':
                $allowed = ($user->role_id == 1);
                break;
            case 'staff':
                $allowed = ($user->role_id == 2);
                break;
            case 'admin_or_staff':
                $allowed = ($user->role_id == 1 || $user->role_id == 2);
                break;
        }
        
        if (!$allowed) {
            app()->route->redirect('/dashboard');
        }
        
        return $request;
    }
}