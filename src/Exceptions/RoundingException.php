<?php

namespace ReeStyleIt\NumberClass\Exceptions;

class RoundingException extends BaseInvalidArgumentException
{
    protected const string DEFAULT_MESSAGE = 'Rounding mode must be a valid RoundingMode case or its integer equivalent.';
}
