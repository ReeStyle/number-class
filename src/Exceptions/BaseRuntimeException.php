<?php

namespace ReeStyleIt\NumberClass\Exceptions;

use RuntimeException;
use Throwable;

class BaseRuntimeException extends RuntimeException
{
    protected const string DEFAULT_MESSAGE = 'Runtime error.';

    public function __construct(?string $message = null, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message ?? static::DEFAULT_MESSAGE, $code, $previous);
    }
}
