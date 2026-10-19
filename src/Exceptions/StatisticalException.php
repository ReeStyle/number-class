<?php

namespace ReeStyleIt\NumberClass\Exceptions;

class StatisticalException extends BaseInvalidArgumentException
{
    protected const string DEFAULT_MESSAGE = 'Statistics require a non-empty sample of finite integers or floats.';
}
