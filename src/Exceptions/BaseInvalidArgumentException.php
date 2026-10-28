<?php

namespace ReeStyleIt\NumberClass\Exceptions;

use InvalidArgumentException;
use Throwable;
use ReeStyleIt\NumberClass\Contracts\NumberException;

class BaseInvalidArgumentException extends InvalidArgumentException implements NumberException
{
    protected const string DEFAULT_MESSAGE = 'Invalid argument.';

    public function __construct(?string $message = null, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message ?? static::DEFAULT_MESSAGE, $code, $previous);
    }
}
