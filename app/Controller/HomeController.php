<?php

namespace Controller;

class HomeController
{
    public function index(): void
    {
        if (app()->auth::check()) {
            app()->route->redirect('/dashboard');
        }
        app()->route->redirect('/login');
    }
}