<?php

namespace Middlewares;

use Src\Request;
use TrimMiddleware\TrimMiddleware as BaseTrimMiddleware;

class TrimMiddleware
{
    private BaseTrimMiddleware $trimMiddleware;

    public function __construct()
    {
        $this->trimMiddleware = new BaseTrimMiddleware();
    }

    public function handle(Request $request): Request
    {
        return $this->trimMiddleware->handle($request);
    }
}