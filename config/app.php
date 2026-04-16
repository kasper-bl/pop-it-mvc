<?php

return [
    'auth' => \Src\Auth\Auth::class,
    'identity' => \Model\Staff::class,

    'routeMiddleware' => [
        'auth' => \Middlewares\AuthMiddleware::class,
    ],
    
    'routeAppMiddleware' => [
        'trim' => \Middlewares\TrimMiddleware::class,
        'specialChars' => \Middlewares\SpecialCharsMiddleware::class,
        'csrf' => \Middlewares\CSRFMiddleware::class,
    ],
    
    'validators' => [  
        'required' => \Validators\RequiredValidator::class,
        'unique' => \Validators\UniqueValidator::class,      
    ],
];