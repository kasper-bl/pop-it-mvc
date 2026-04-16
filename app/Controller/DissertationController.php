<?php

namespace Controller;

use Src\View;

class DissertationController
{
    public function index(): string
    {
        $user = app()->auth::user();
        $isAdmin = ($user->id_role == 1);
        
        return new View('site.dissertations', [
            'isAdmin' => $isAdmin
        ]);
    }
}