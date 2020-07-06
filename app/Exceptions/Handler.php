<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        if ($exception instanceof NotFoundHttpException) {
            return $request->expectsJson() ? response()->jsr(404, [], 'Path:' .$request->path(). '不存在') : parent::render($request, $exception);
        }
        if ($request->expectsJson() || Str::startsWith($request->route()->uri(), 'api/')) {
            if ($exception instanceof  CustomException) {
                return response()->jsr(500, [], $exception->getMessage());
            } elseif ($exception instanceof LoginFailedException) {
                return response()->jsr(500, [], $exception->getMessage());
            } elseif ($exception instanceof AuthenticationException) {
                return response()->jsr(403, [], '令牌已过期，需要重新登录');
            } elseif ($exception instanceof ValidationException) {
                return response()->jsr(501, [], Arr::first($exception->errors())[0]);
            } elseif ($exception instanceof ModelNotFoundException) {
                return response()->jsr(404, [], '没有查询到符合的记录');
            }
        }

        return parent::render($request, $exception);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $error = Arr::first($e->errors());
        if ($request->expectsJson()) {
            return response()->jsr(500, [], $error[0] ?? "输入的参数有误，请检查");
        }
        return parent::convertValidationExceptionToResponse($e, $request);
    }
}
