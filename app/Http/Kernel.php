<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Global middleware
    ];

    protected $routeMiddleware = [
        'check.token' => \App\Http\Middleware\CheckToken::class,
        // Other middleware
    ];
}
