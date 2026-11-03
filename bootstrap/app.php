<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Unexpected failures in form submissions return to the form with a flash
        // message. They are still written to the log by Laravel's report step.
        $exceptions->render(function (Throwable $e, Request $request) {
            $isExpected = $e instanceof ValidationException
                || $e instanceof HttpExceptionInterface
                || $e instanceof ModelNotFoundException
                || $e instanceof TokenMismatchException;

            if ($request->isMethodSafe() || $isExpected) {
                return null;
            }

            return back()->withInput()->with('error', 'حدث خطأ أثناء تنفيذ العملية.');
        });
    })->create();
