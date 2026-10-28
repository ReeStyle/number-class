<?php

namespace ReeStyleIt\NumberClass\Number;

use IntlException;
use NumberFormatter;
use ReeStyleIt\NumberClass\Exceptions\NumberFormattingException;

/** @internal Converts values already validated by the parent Number class. */
readonly class Wordify
{
    public function __construct(private int|float $value, private string $locale) {}

    public function format(): string
    {
        if (! class_exists(NumberFormatter::class)) {
            throw NumberFormattingException::intlRequired();
        }

        try {
            $formatter = new NumberFormatter($this->locale, NumberFormatter::SPELLOUT);
            $result = $formatter->format($this->value, is_int($this->value) ? NumberFormatter::TYPE_INT64 : NumberFormatter::TYPE_DOUBLE);
        } catch (IntlException $exception) {
            throw new NumberFormattingException($exception->getMessage(), previous: $exception);
        }

        if ($result === false) {
            throw new NumberFormattingException($formatter->getErrorMessage());
        }

        return str_replace("\u{00AD}", '', $result);
    }
}
