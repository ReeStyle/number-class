<?php

namespace ReeStyleIt\NumberClass\Exceptions;

use RuntimeException;
use Throwable;
use ReeStyleIt\NumberClass\Contracts\NumberException;

class BaseRuntimeException extends RuntimeException implements NumberException
{
    protected const string DEFAULT_MESSAGE = 'Runtime error.';

    public function __construct(?string $message = null, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message ?? static::DEFAULT_MESSAGE, $code, $previous);
    }
}
