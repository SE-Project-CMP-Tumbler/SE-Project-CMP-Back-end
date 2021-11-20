<?php

namespace App\Exceptions;

use App\Http\Misc\Traits\WebServiceResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Misc\Helpers\Errors;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    use WebServiceResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
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
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->errorResponse($this->modelName($exception), "404");
            } elseif ($exception instanceof AuthorizationException) {
                return $this->errorResponse(Errors::UNAUTHORIZED, "401");
            } elseif ($exception instanceof AuthenticationException) {
                return $this->errorResponse(Errors::UNAUTHENTICATED, "403");
            } elseif ($exception instanceof ValidationException) {
                return $this->errorResponse($exception->validator->errors()->first(), (string) $exception->status);
            } elseif ($exception instanceof NotFoundHttpException) {
                return $this->errorResponse(Errors::TESTING, "404");
            } elseif ($exception instanceof MethodNotAllowedHttpException) {
                return $this->errorResponse(Errors::NOTALLOWED, "405");
            }
            // } elseif ($exception instanceof QueryException) {
            //       return $this->error_response(Errors::TESTING, "404");
            // }
        }
        return parent::render($request, $exception);
    }
    private function printValidationError($errors)
    {
        $errors_txt = "";
        foreach ($errors as $message) {
            $errors_txt .= $message;
        }
        return $errors_txt;
    }
    private function modelName(ModelNotFoundException $exception)
    {
        $model = $exception->getModel();
        $model = substr($model, 11, strlen($model) - 1);
        $msg = "Not Found " . $model;
        return $msg;
    }
}
