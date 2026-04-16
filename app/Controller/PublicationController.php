<?php

namespace Controller;

use Src\View;

class PublicationController
{
    public function index(): string
    {
        $user = app()->auth::user();
        $isAdmin = ($user->id_role == 1);
        
        return new View('site.publications', [
            'isAdmin' => $isAdmin
        ]);
    }
}