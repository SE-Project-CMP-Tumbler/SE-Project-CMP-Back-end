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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
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
                return $this->error_response(Errors::TESTING, "404");
            } elseif ($exception instanceof AuthorizationException) {
                return $this->error_response(Errors::UNAUTHORIZED, "401");
            } elseif ($exception instanceof AuthenticationException) {
                return $this->error_response(Errors::UNAUTHENTICATED, "403");
            } elseif ($exception instanceof ValidationException) {
                return $this->error_response($this->print_validation_errors($exception->validator->errors()->all()), "422");
            }
        }
        return parent::render($request, $exception);
    }
    private function print_validation_errors($errors)
    {
        $errors_txt = "";
        foreach($errors as $message)
            $errors_txt .= $message."\n";
        return $errors_txt;
    }

}
