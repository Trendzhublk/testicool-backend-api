<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Mail;
use App\Mail\ErrorReportMail;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\Illuminate\Http\Middleware\HandleCors::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->reportable(function (Throwable $e) {
            try {
                $request = request();
                $context = [
                    'request' => [
                        'method' => $request?->method(),
                        'url' => $request?->fullUrl(),
                        'ip' => $request?->ip(),
                        'user' => optional($request?->user())->email ?? 'guest',
                        'headers' => $request?->headers->all() ?? [],
                        'body' => $request?->all() ?? [],
                    ],
                    'session' => $request?->session()?->all() ?? [],
                ];

                Mail::to('hettigetharukaidushan@gmail.com')
                    ->send(new ErrorReportMail($e, $context));
            } catch (\Throwable $ignored) {
                // Swallow to avoid cascading failures in exception handler
            }
        });
    })->create();
