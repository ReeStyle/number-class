<?php

namespace ReeStyleIt\NumberClass\Exceptions;

class MustBeIntegerException extends BaseInvalidArgumentException
{
    protected const string DEFAULT_MESSAGE = 'Value must be an integer.';
}
