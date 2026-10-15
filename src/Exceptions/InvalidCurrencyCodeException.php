<?php

namespace ReeStyleIt\NumberClass\Exceptions;

class InvalidCurrencyCodeException extends BaseInvalidArgumentException
{
    protected const string DEFAULT_MESSAGE = 'Currency must be a three-letter currency code.';
}
