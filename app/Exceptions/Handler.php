<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)

    {
        // Check if the request expects a JSON response, typically for API requests
        if ($request->is('api') || $request->is('api/*')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Redirect to Filament login for admin routes, or fallback to a generic login
        if ($request->is('admin') || $request->is('admin/*')) {
            return redirect()->guest(route('filament.admin.auth.login'));  // Adjust this based on your actual route setup
        }

        // Redirect to a general login route if it exists
        return redirect()->guest(route('login'));
    }
}
