<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'error' => 'The method is not allowed for the requested route. Please use POST.',
            ], 405);
        }

        if ($exception instanceof UnauthorizedHttpException && $exception->getPrevious() instanceof TokenExpiredException) {
            return response()->json(['error' => 'Token has expired', 'responseCode' => 401], 401);
        }
    
        if ($exception instanceof UnauthorizedHttpException && $exception->getPrevious() instanceof TokenInvalidException) {
            return response()->json(['error' => 'Token is invalid', 'responseCode' => 401], 401);
        }
    
        if ($exception instanceof UnauthorizedHttpException && $exception->getPrevious() instanceof JWTException) {
            return response()->json(['error' => 'Token not provided', 'responseCode' => 401], 401);
        }
    

        return parent::render($request, $exception);
    }

}
