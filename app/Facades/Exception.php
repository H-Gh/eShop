<?php

namespace App\Facades;

use App\Services\ExceptionHandlerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Facade;
use RuntimeException;
use Throwable;

/**
 * The facades that handle the exceptions
 * PHP version >= 7.0
 *
 * @category Facades
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 * @method static Throwable handle(Throwable $exception);
 * @method static JsonResponse renderJson(Throwable $exception);
 * @see      ExceptionHandlerService
 */
class Exception extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     * @throws RuntimeException
     */
    protected static function getFacadeAccessor(): string
    {
        return 'exception_handler';
    }
}
