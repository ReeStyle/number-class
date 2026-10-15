<?php

namespace ReeStyleIt\NumberClass\Exceptions;

use Throwable;

class CurrencyFormattingException extends BaseInvalidArgumentException
{
    protected const string DEFAULT_MESSAGE = 'Currency formatting failed.';

    public function __construct(string $details = '', int $code = 0, ?Throwable $previous = null)
    {
        $message = $details === '' ? self::DEFAULT_MESSAGE : 'Currency formatting failed: ' . $details;

        parent::__construct($message, $code, $previous);
    }
}
