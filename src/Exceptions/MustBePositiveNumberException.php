<?php

namespace ReeStyleIt\NumberClass\Exceptions;

class MustBePositiveNumberException extends BaseInvalidArgumentException
{
    protected const string DEFAULT_MESSAGE = 'Number must be greater than 0.';

    public static function rootExponent(): self
    {
        return new self('Root exponent must be greater than 0.');
    }

    public static function multiple(): self
    {
        return new self('Multiple must be a positive finite number.');
    }
}
