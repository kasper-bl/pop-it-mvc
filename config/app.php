<?php

return [
    'auth' => \Src\Auth\Auth::class,
    'identity' => \Model\Staff::class,
    'routeMiddleware' => [
        'auth' => \Middlewares\AuthMiddleware::class,
    ],
];