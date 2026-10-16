<?php

namespace ReeStyleIt\NumberClass\Exceptions;

use Throwable;

class TimeException extends BaseInvalidArgumentException
{
    protected const string DEFAULT_MESSAGE = 'Time must be a non-negative finite number of seconds.';

    public static function invalidStartingPoint(?Throwable $previous = null): self
    {
        return new self('Starting point must be a valid Unix timestamp or date.', previous: $previous);
    }

    public static function tooLarge(): self
    {
        return new self('Time is too large to dissect into whole seconds.');
    }
}
