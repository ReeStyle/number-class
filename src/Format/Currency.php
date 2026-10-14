<?php

namespace ReeStyleIt\NumberClass\Format;

use InvalidArgumentException;
use NumberFormatter;
use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Enums\Currencies;
use RuntimeException;
use UnexpectedValueException;

class Currency extends Base
{
    public string $currency = 'EUR';
    public string $locale = 'nl_NL';

    public function currency(Currencies|string $currency): self
    {
        $currency = $currency instanceof Currencies ? $currency->value : strtoupper($currency);

        if (!preg_match('/^[A-Z]{3}$/D', $currency)) {
            throw new InvalidArgumentException('Currency must be a three-letter currency code.');
        }

        $this->currency = $currency;

        return $this;
    }

    public function locale(string $locale): self
    {
        if (trim($locale) === '') {
            throw new InvalidArgumentException('Locale must not be empty.');
        }

        $this->locale = $locale;

        return $this;
    }

    public function __toString(): string
    {
        $this->assertNumber();

        if (!is_finite($this->value)) {
            throw new UnexpectedValueException('Currency value must be finite.');
        }

        $formatter = new NumberFormatter($this->locale, NumberFormatter::CURRENCY);
        $result = $formatter->formatCurrency($this->value, $this->currency);

        if ($result === false) {
            throw new RuntimeException('Currency formatting failed: ' . $formatter->getErrorMessage());
        }

        return $result;
    }
}
