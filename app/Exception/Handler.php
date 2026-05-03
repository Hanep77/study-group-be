<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            // Validation Exception
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $exception->errors()
                ], 422);
            }

            // Model Not Found
            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'message' => 'Resource not found'
                ], 404);
            }

            // Authentication
            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            }

            // Authorization
            if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Generic errors
            return response()->json([
                'message' => 'Server error',
                'error' => config('app.debug') ? $exception->getMessage() : null
            ], 500);
        }

        return parent::render($request, $exception);
    }
}