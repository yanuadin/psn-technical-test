<?php

namespace App\Exceptions;

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
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/customer/*')) { // <- Add your condition here
                return response()->json([
                    'is_success' => false,
                    'message' => 'Customer(s) record not found.',
                    'data' => null,
                ], 404);
            } else if ($request->is('api/address/*')) {
                return response()->json([
                    'is_success' => false,
                    'message' => 'Address(es) record not found.',
                    'data' => null,
                ], 404);
            }
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function shouldReturnJson($request, Throwable $e)
    {
        return true;
    }
}
