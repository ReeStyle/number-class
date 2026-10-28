<?php

namespace ReeStyleIt\NumberClass\Format;

use NumberFormatter;
use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Enums\Currencies;
use ReeStyleIt\NumberClass\Enums\Locales;
use ReeStyleIt\NumberClass\Exceptions\CurrencyFormattingException;
use ReeStyleIt\NumberClass\Exceptions\EmptyLocaleException;
use ReeStyleIt\NumberClass\Exceptions\InvalidCurrencyCodeException;
use ReeStyleIt\NumberClass\Exceptions\MustBeFiniteCurrencyValue;

class Currency extends Base
{
    public string $currencyCode = 'EUR';
    public string $locale = 'nl_NL';

    public function __construct(mixed $value)
    {
        parent::__construct($value);

        $this->locale = self::getDefaultLocale() ?? $this->locale;
    }

    public function currency(Currencies|string|null $currency = null): self
    {
        if ($currency === null) {
            return parent::currency();
        }

        $currency = $currency instanceof Currencies ? $currency->value : strtoupper($currency);

        if (! preg_match('/^[A-Z]{3}$/D', $currency)) {
            throw new InvalidCurrencyCodeException();
        }

        $this->currencyCode = $currency;

        return $this;
    }

    public function locale(Locales|string $locale): self
    {
        $locale = $locale instanceof Locales ? $locale->value : $locale;

        if (trim($locale) === '') {
            throw new EmptyLocaleException();
        }

        $this->locale = $locale;

        return $this;
    }

    public function __toString(): string
    {
        $this->assertNumber();

        if (! is_finite($this->value)) {
            throw new MustBeFiniteCurrencyValue();
        }

        if (! class_exists(NumberFormatter::class)) {
            throw CurrencyFormattingException::intlRequired();
        }

        $formatter = new NumberFormatter($this->locale, NumberFormatter::CURRENCY);
        $result = $formatter->formatCurrency($this->value, $this->currencyCode);

        if ($result === false) {
            throw new CurrencyFormattingException($formatter->getErrorMessage());
        }

        return $result;
    }
}
