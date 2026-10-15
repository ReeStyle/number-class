<?php

namespace ReeStyleIt\NumberClass\Exceptions;

use Throwable;

class CurrencySymbolLookupException extends BaseRuntimeException
{
    protected const string DEFAULT_MESSAGE = 'Currency symbol lookup failed.';

    public function __construct(string $details = '', int $code = 0, ?Throwable $previous = null)
    {
        $message = $details === '' ? self::DEFAULT_MESSAGE : 'Currency symbol lookup failed: ' . $details;

        parent::__construct($message, $code, $previous);
    }
}
