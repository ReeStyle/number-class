<?php

namespace ReeStyleIt\NumberClass\Exceptions;

class PythagorasException extends BaseInvalidArgumentException
{
    protected const string DEFAULT_MESSAGE = 'Invalid right triangle dimensions.';

    public static function legTooLong(): self
    {
        return new self('A leg cannot be longer than the hypotenuse.');
    }

    public static function invalidLength(): self
    {
        return new self('Length must be a non-negative finite number.');
    }
}
