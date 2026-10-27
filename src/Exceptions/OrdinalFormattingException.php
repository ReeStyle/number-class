<?php

namespace ReeStyleIt\NumberClass\Exceptions;

use Throwable;

class OrdinalFormattingException extends BaseInvalidArgumentException
{
    protected const string DEFAULT_MESSAGE = 'Ordinal formatting failed.';

    public function __construct(string $details = '', int $code = 0, ?Throwable $previous = null)
    {
        $message = $details === '' ? self::DEFAULT_MESSAGE : 'Ordinal formatting failed: ' . $details;

        parent::__construct($message, $code, $previous);
    }

    public static function intlRequired(): self
    {
        return new self('The intl extension is required for ordinal formatting.');
    }
}
