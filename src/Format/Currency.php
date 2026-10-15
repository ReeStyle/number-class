<?php

namespace ReeStyleIt\NumberClass\Format;

use NumberFormatter;
use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Enums\Currencies;
use ReeStyleIt\NumberClass\Exceptions\CurrencyFormattingException;
use ReeStyleIt\NumberClass\Exceptions\EmptyLocaleException;
use ReeStyleIt\NumberClass\Exceptions\InvalidCurrencyCodeException;
use ReeStyleIt\NumberClass\Exceptions\MustBeFiniteCurrencyValue;

class Currency extends Base
{
    public string $currency = 'EUR';
    public string $locale = 'nl_NL';

    public function currency(Currencies|string $currency): self
    {
        $currency = $currency instanceof Currencies ? $currency->value : strtoupper($currency);

        if (!preg_match('/^[A-Z]{3}$/D', $currency)) {
            throw new InvalidCurrencyCodeException();
        }

        $this->currency = $currency;

        return $this;
    }

    public function locale(string $locale): self
    {
        if (trim($locale) === '') {
            throw new EmptyLocaleException();
        }

        $this->locale = $locale;

        return $this;
    }

    public function __toString(): string
    {
        $this->assertNumber();

        if (!is_finite($this->value)) {
            throw new MustBeFiniteCurrencyValue();
        }

        $formatter = new NumberFormatter($this->locale, NumberFormatter::CURRENCY);
        $result = $formatter->formatCurrency($this->value, $this->currency);

        if ($result === false) {
            throw new CurrencyFormattingException($formatter->getErrorMessage());
        }

        return $result;
    }
}
