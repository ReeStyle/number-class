<?php

namespace ReeStyleIt\NumberClass\Exceptions;

class FinancialException extends BaseInvalidArgumentException
{
    protected const string DEFAULT_MESSAGE = 'Invalid financial calculation.';
}
