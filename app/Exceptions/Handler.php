<?php

namespace App\Exceptions;

use Anik\Form\ValidationException as FormValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        UnauthorizedException::class,
        ValidationException::class,
        FormValidationException::class,
    ];

    public function report (Throwable $exception) {
        parent::report($exception);
    }

    public function render ($request, Throwable $exception) {
        switch ( true ) {
            case $exception instanceof UnauthorizedException:
                return $this->responder([
                    'error'   => true,
                    'message' => $exception->getMessage() ?: 'Unauthorized.',
                ], 401);
            case $exception instanceof QueryException:
                return $this->responder([
                    'error'   => true,
                    'message' => 'Something went wrong.',
                ], 500);
            case $exception instanceof FormValidationException:
            case $exception instanceof ValidationException:
                return $this->parseValidationErrorResponse($exception);
            case $exception instanceof ModelNotFoundException:
            case $exception instanceof NotFoundHttpException:
                return $this->responder([
                    'error'   => true,
                    'message' => 'Resource not available.',
                ], 404);
            case $exception instanceof MethodNotAllowedHttpException:
                $message = 'Method now allowed.';
            case $exception instanceof HttpException:
                $statusCode = $exception->getStatusCode();
                $message = $message ?? $exception->getMessage();
                $headers = $exception->getHeaders() ?: [];

                return $this->responder([
                    'error'   => true,
                    'message' => $message,
                ], $statusCode, $headers);
            default:
                $data = [
                    'error'   => true,
                    'message' => 'Something went wrong',
                ];
                $statusCode = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;

                return $this->responder($data, $statusCode);
        }
    }

    private function responder ($data, $statusCode, array $headers = []) {
        return response()->json($data, $statusCode, $headers);
    }

    private function parseValidationErrorResponse (Throwable $exception) {
        $errors = [];
        $statusCode = 422;
        if ($exception instanceof ValidationException) {
            $errors = $exception->errors();
        } elseif ($exception instanceof FormValidationException) {
            $errors = $exception->getResponse();
        }
        $reasons = [];
        foreach ( $errors as $key => $error ) {
            $reasons[$key] = $error[0];
        }

        return $this->responder([ 'error' => true, 'reasons' => $reasons ], $statusCode);
    }
}
