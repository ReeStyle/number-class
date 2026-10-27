<?php

namespace ReeStyleIt\NumberClass\Conversion;

use IntlException;
use Locale;
use NumberFormatter;
use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Enums\Locales;
use ReeStyleIt\NumberClass\Exceptions\EmptyLocaleException;
use ReeStyleIt\NumberClass\Exceptions\MustBeIntegerException;
use ReeStyleIt\NumberClass\Exceptions\OrdinalFormattingException;

class Ordinal extends Base
{
    public string $locale = 'en_US';

    public function locale(Locales|string $locale): self
    {
        $locale = $locale instanceof Locales ? $locale->value : $locale;

        if (trim($locale) === '') {
            throw new EmptyLocaleException();
        }

        $this->locale = $locale;

        return $this;
    }

    public function format(): string
    {
        $this->assertNumber();

        if (! is_int($this->value)) {
            throw new MustBeIntegerException();
        }

        // ICU's rule-based formatter uses doubles internally for ordinal rules.
        if ($this->value < -9007199254740991 || $this->value > 9007199254740991) {
            throw new OrdinalFormattingException('Value must be between -9007199254740991 and 9007199254740991.');
        }

        if (trim($this->locale) === '') {
            throw new EmptyLocaleException();
        }

        if (! class_exists(NumberFormatter::class)) {
            throw OrdinalFormattingException::intlRequired();
        }

        try {
            $formatter = new NumberFormatter($this->locale, NumberFormatter::ORDINAL);
            $result = $formatter->format($this->value, NumberFormatter::TYPE_INT64);
        } catch (IntlException $exception) {
            throw new OrdinalFormattingException($exception->getMessage(), previous: $exception);
        }

        if ($result === false) {
            throw new OrdinalFormattingException($formatter->getErrorMessage());
        }

        if (Locale::getPrimaryLanguage($this->locale) === 'nl') {
            $ending = abs($this->value % 100);
            $suffix = $ending === 0 || $ending === 1 || $ending === 8 || $ending >= 20 ? 'ste' : 'de';

            // ICU abbreviates Dutch ordinals with "e"; expose the full suffix.
            return preg_replace('/e$/D', $suffix, $result);
        }

        return $result;
    }

    public function __toString(): string
    {
        return $this->format();
    }
}
