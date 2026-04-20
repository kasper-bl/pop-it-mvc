<?php

namespace Middlewares;

use Src\Request;

class TrimMiddleware

{
    public function handle(Request $request): Request
    {
        foreach ($request->all() as $key => $value) {
            if (is_string($value)) {
                $request->set($key, trim($value));
            }
        }
        
        return $request;
    }
}