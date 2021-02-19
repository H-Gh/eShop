<?php

namespace App\Exceptions;

use App\Exceptions\Interfaces\ShouldPublish;
use Throwable;

/**
 * An exception to use when something is missing
 * PHP version >= 7.0
 *
 * @category Exceptions
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class NotFoundException extends BaseException implements ShouldPublish
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
    public function __construct($message = "Not found.", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
