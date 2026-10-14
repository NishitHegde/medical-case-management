<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                if ($e instanceof AuthenticationException) {
                    return response()->json([
                        'success' => false,
                        'data' => null,
                        'message' => 'Unauthenticated. Invalid or missing token.',
                    ], 401);
                }

                if ($e instanceof AuthorizationException || $e instanceof AccessDeniedHttpException) {
                    return response()->json([
                        'success' => false,
                        'data' => null,
                        'message' => 'Forbidden. You do not have permission to perform this action.',
                    ], 403);
                }

                if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                    return response()->json([
                        'success' => false,
                        'data' => null,
                        'message' => 'Resource not found.',
                    ], 404);
                }

                if ($e instanceof ValidationException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failure.',
                        'errors' => $e->errors(),
                    ], 422);
                }
            }
        });
    }
}
