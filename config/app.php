<?php

return [
    'auth' => \Src\Auth\Auth::class,
    'identity' => \Model\Staff::class,

    'routeMiddleware' => [
        'auth' => \Middlewares\AuthMiddleware::class,
        'role' => \Middlewares\RoleMiddleware::class,
        'owner' => \Middlewares\OwnerMiddleware::class,
    ],
    
    'routeAppMiddleware' => [
        'trim' => \Middlewares\TrimMiddleware::class,
        'specialChars' => \Middlewares\SpecialCharsMiddleware::class,
        'csrf' => \Middlewares\CSRFMiddleware::class,
    ],
    
    'validators' => [
        'required' => \Validators\RequiredValidator::class,
        'unique' => \Validators\UniqueValidator::class,
        'file' => \Validators\FileValidator::class,
        'date_range' => \Validators\DateRangeValidator::class,
        'year' => \Validators\YearValidator::class,
    ],
];