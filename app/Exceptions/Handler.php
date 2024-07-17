<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        // if( $e->getStatusCode() != '422' && $request->ajax() )
        // {
        //     if (app()->environment('local'))
        //     {
        //         return response()->json(['error2'=> $e->getMessage()]);
        //     }
        //     return response()->json(['error2'=> "Something went wrong on server, while performing this action"]);
        // }

        if (!$request->is('api/*')) {
            return parent::render($request, $e);
        }
        if ($e instanceof AuthenticationException){
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => __($e->getMessage()),
            ], 401);
        }
        if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException){
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $e->getMessage() ?? __("Not found"),
            ], 404);
        }
        if ($e instanceof AuthorizationException){
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => __($e->getMessage()),
            ], 403);
        }

        return parent::render($request, $e);
    }
}
