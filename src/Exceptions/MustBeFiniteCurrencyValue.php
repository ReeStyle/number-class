<?php

namespace ReeStyleIt\NumberClass\Exceptions;

class MustBeFiniteCurrencyValue extends BaseInvalidArgumentException
{
    protected const string DEFAULT_MESSAGE = 'Currency value must be finite.';
}
