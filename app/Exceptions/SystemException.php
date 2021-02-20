<?php

namespace App\Exceptions;

use App\Exceptions\Interfaces\ShouldPublish;
use Throwable;

/**
 * A general exception to throw when we don't want to output an exception
 * PHP version >= 7.0
 *
 * @category Exceptions
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class SystemException extends BaseException implements ShouldPublish
{
    /**
     * Construct the exception. Note: The message is NOT binary safe.
     *
     * @link https://php.net/manual/en/exception.construct.php
     *
     * @param string         $message  [optional] The Exception message to throw.
     * @param int            $code     [optional] The Exception code.
     * @param Throwable|null $previous [optional] The previous throwable used for the exception chaining.
     */
    public function __construct($message = "Something is wrong here.", $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
