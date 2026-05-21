<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware): void {
    $middleware->redirectGuestsTo('/login');
    $middleware->alias([
      'portal' => \App\Http\Middleware\EnsurePortalUser::class,
      'account.active' => \App\Http\Middleware\EnsureAccountIsActive::class,
      'dashboard' => \App\Http\Middleware\EnsureUserCanAccessDashboard::class,
      'super_admin' => \App\Http\Middleware\EnsureUserIsSuperAdmin::class,
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions): void {
    //
  })->create();
