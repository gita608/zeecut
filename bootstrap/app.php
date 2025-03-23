<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Http\Middleware\ForceJsonResponse; // Import the middleware

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
<<<<<<< HEAD
        // ✅ Apply Sanctum only to API routes
        $middleware->group('api', [
            EnsureFrontendRequestsAreStateful::class,
            ForceJsonResponse::class, // Add this middleware
        ]);

=======
>>>>>>> rabil
        // ✅ Register custom API middleware
        $middleware->alias([
            'api.auth' => ApiAuthMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

