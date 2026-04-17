<?php

return [
    'auth' => \Src\Auth\Auth::class,
    'identity' => \Model\Staff::class,

    'routeMiddleware' => [
        'auth' => \Middlewares\AuthMiddleware::class,
    ],
    
    'validators' => [
        'required' => \Validators\RequiredValidator::class,
        'unique' => \Validators\UniqueValidator::class,
        'file' => \Validators\FileValidator::class,
        'date_range' => \Validators\DateRangeValidator::class,
        'year' => \Validators\YearValidator::class,
    ],

    'routeMiddleware' => [
        'auth' => \Middlewares\AuthMiddleware::class,
        'role' => \Middlewares\RoleMiddleware::class,
        'owner' => \Middlewares\OwnerMiddleware::class,
    ],
];