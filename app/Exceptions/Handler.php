<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
// use Symfony\Component\HttpKernel\Exception\AccessDeniedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use App\Constants\HttpCode;
use Throwable;

class Handler extends ExceptionHandler
{
    use \App\Traits\ResponseMessageTrait;

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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

        // Handle model not found exceptions
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/v1/*')) {
                if ($e instanceof ModelNotFoundException) {
                    return $this->fails($e->getMessage(), HttpCode::NO_CONTENT);
                }

                return $this->fails($e->getMessage(), HttpCode::NOT_FOUND);
            }
        });

        // Handle method not allowed responses.
        $this->renderable(fn (AuthorizationException $exception) => $this->fails($exception->getMessage(), HttpCode::UNAUTHENTICATED));

        // Handle method not allowed responses.
        $this->renderable(fn (MethodNotAllowedHttpException $exception) => $this->fails(__('message.page_not_found'), HttpCode::NOT_FOUND));

        // Handle query column not found responses.
        $this->renderable(fn (QueryException $exception, $request) => $this->fails($exception->getPrevious()->getMessage(), HttpCode::FAIL));
    }

    /**
     * Handle if user unauthenticated.
     * @param  \Illuminate\Http\Request  $request
     * @param AuthenticationException  $exception
     * 
     * @return redirect|response
     *
     * @return void
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? $this->fails($exception->getMessage(), HttpCode::UNAUTHENTICATED)
            : redirect()->guest(route('login'));
    }
}
