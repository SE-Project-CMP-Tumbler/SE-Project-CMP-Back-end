<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
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
<<<<<<< Updated upstream
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
=======
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
                return $this->error_response($this->printValidationError($exception->validator->errors()->all()), (string)$exception->status);
            }
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
>>>>>>> Stashed changes
    }
}
