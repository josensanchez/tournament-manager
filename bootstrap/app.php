<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\ModelStates\Exceptions\TransitionNotAllowed;
use Spatie\ModelStates\Exceptions\TransitionNotFound;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        api: __DIR__ . '/../routes/api.php',
        health: '/health',
    )
    ->withMiddleware(function (Middleware $middleware): void {})
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->dontReport([
            TransitionNotFound::class,
        ]);
        $exceptions->renderable(function (Exception $e, $request) {
            // Handle renderable exceptions
            if ($e instanceof TransitionNotFound) {
                return response()->json(['error' => 'Transition not found'], 422);
            }
            if ($e instanceof TransitionNotAllowed) {
                return response()->json([
                    'error' => 'Transition not allowed',
                    'message' => $e->getMessage(),
                ], 422);
            }

            dd($e);

            return response()->json([
                'error' => 'An error occurred while processing your request',
                'message' => $e->getMessage(),
            ], 500);
        });
    })->create();
