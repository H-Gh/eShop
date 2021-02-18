<?php

namespace App\Services;

use App\Exceptions\Interfaces\ShouldPublish;
use App\Exceptions\SystemException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use \Illuminate\Http\Response;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 * The service to handle the exceptions
 * PHP version >= 7.0
 *
 * @category Services
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class ExceptionHandlerService
{
    /**
     * This method handle the throwable
     *
     * @param Throwable $throwable
     *
     * @return Throwable
     */
    public function handle(Throwable $throwable): Throwable
    {
        if ($throwable instanceof ShouldPublish ||
            $throwable instanceof ValidationException ||
            $throwable instanceof ModelNotFoundException) {
            return $throwable;
        } else {
            return new SystemException();
        }
    }

    /**
     * @param Throwable $exception
     *
     * @return JsonResponse
     */
    public function renderJson(Throwable $exception): JsonResponse
    {
        return ResponseFacade::json(["message" => $exception->getMessage()], $this->getCode($exception));
    }

    /**
     * @param Throwable $exception
     *
     * @return int
     */
    private function getCode(Throwable $exception): int
    {
        $code = ($exception->getCode() < 200 || $exception->getCode() >= 600) ? 500 : $exception->getCode();
        if (isset($exception->status)) {
            $code = $exception->status;
        }
        return $code;
    }
}
