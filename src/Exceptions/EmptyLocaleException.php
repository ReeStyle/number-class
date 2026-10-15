<?php

namespace ReeStyleIt\NumberClass\Exceptions;

class EmptyLocaleException extends BaseInvalidArgumentException
{
    protected const string DEFAULT_MESSAGE = 'Locale must not be empty.';
}
