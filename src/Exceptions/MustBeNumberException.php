<?php

namespace ReeStyleIt\NumberClass\Exceptions;

class MustBeNumberException extends BaseInvalidArgumentException
{
    protected const string DEFAULT_MESSAGE = 'Value must be an integer or float.';
}
