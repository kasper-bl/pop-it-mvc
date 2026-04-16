<?php

namespace Controller;

use Src\View;

class SearchController
{
    public function index(): string
    {
        $user = app()->auth::user();
        
        // Только для сотрудников
        if ($user->id_role == 1) {
            app()->route->redirect('/dashboard');
        }
        
        return new View('site.search');
    }
}