<?php

namespace ReeStyleIt\NumberClass\Conversion;

use IntlException;
use Locale as IntlLocale;
use NumberFormatter;
use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Conversion\Ordinal\Wordify;
use ReeStyleIt\NumberClass\Enum\Locale;
use ReeStyleIt\NumberClass\Exceptions\EmptyLocaleException;
use ReeStyleIt\NumberClass\Exceptions\MustBeIntegerException;
use ReeStyleIt\NumberClass\Exceptions\OrdinalFormattingException;

class Ordinal extends Base
{
    public string $locale = 'en_US';

    public string $wordify {
        get {
            return $this->wordify();
        }
    }

    public function __construct(mixed $value)
    {
        parent::__construct($value);

        $this->locale = self::getDefaultLocale() ?? $this->locale;
    }

    public function locale(Locale|string $locale): self
    {
        $locale = $locale instanceof Locale ? $locale->value : $locale;

        if (trim($locale) === '') {
            throw new EmptyLocaleException();
        }

        $this->locale = $locale;

        return $this;
    }

    public function format(): string
    {
        $this->assertFormattable();

        try {
            $formatter = new NumberFormatter($this->locale, NumberFormatter::ORDINAL);
            $result = $formatter->format($this->value, NumberFormatter::TYPE_INT64);
        } catch (IntlException $exception) {
            throw new OrdinalFormattingException($exception->getMessage(), previous: $exception);
        }

        if ($result === false) {
            throw new OrdinalFormattingException($formatter->getErrorMessage());
        }

        if (IntlLocale::getPrimaryLanguage($this->locale) === 'nl') {
            $ending = abs($this->value % 100);
            $suffix = $this->value !== 0 && ($ending === 0 || $ending === 1 || $ending === 8 || $ending >= 20) ? 'ste' : 'de';

            // ICU abbreviates Dutch ordinals with "e"; expose the full suffix.
            return preg_replace('/e$/D', $suffix, $result);
        }

        return $result;
    }

    public function wordify(): string
    {
        $this->assertFormattable();

        return new Wordify($this->value, $this->locale)->format();
    }

    public function __toString(): string
    {
        return $this->format();
    }

    private function assertFormattable(): void
    {
        $this->assertNumber();

        if (! is_int($this->value)) {
            throw new MustBeIntegerException();
        }

        // ICU's rule-based formatter uses doubles internally for ordinal rules.
        if ($this->value < self::MIN_SAFE_INTEGER || $this->value > self::MAX_SAFE_INTEGER) {
            throw OrdinalFormattingException::outOfRange(self::MIN_SAFE_INTEGER, self::MAX_SAFE_INTEGER);
        }

        if (trim($this->locale) === '') {
            throw new EmptyLocaleException();
        }

        if (! class_exists(NumberFormatter::class)) {
            throw OrdinalFormattingException::intlRequired();
        }
    }
}
