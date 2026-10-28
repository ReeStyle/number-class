<?php

namespace ReeStyleIt\NumberClass;

use ReeStyleIt\NumberClass\Enum\Locale;
use ReeStyleIt\NumberClass\Exceptions\EmptyLocaleException;
use ReeStyleIt\NumberClass\Exceptions\NumberFormattingException;
use ReeStyleIt\NumberClass\Mathematical\Arithmetic;
use ReeStyleIt\NumberClass\Number\Wordify;

class Number extends Base
{
    public string $wordify {
        get {
            return $this->wordify();
        }
    }

    public function abs(): Arithmetic
    {
        return $this->arithmetic->abs();
    }

    public function add(int|float $amount = 1): Arithmetic
    {
        return $this->arithmetic->add($amount);
    }

    public function ceil(): Arithmetic
    {
        return $this->arithmetic->ceil();
    }

    public function decrease(int|float $amount = 1): Arithmetic
    {
        return $this->subtract($amount);
    }

    public function divide(int|float $divisor): Arithmetic
    {
        return $this->arithmetic->divide($divisor);
    }

    public function floor(): Arithmetic
    {
        return $this->arithmetic->floor();
    }

    public function format(int $decimals = 0, ?string $decimal_separator = '.', ?string $thousands_separator = ','): string
    {
        $this->assertNumber();

        return number_format($this->value, $decimals, $decimal_separator, $thousands_separator);
    }

    public function increase(int|float $amount = 1): Arithmetic
    {
        return $this->add($amount);
    }

    public function intdiv(int $divisor): Arithmetic
    {
        return $this->arithmetic->intdiv($divisor);
    }

    public function isFloat(): bool
    {
        return is_float($this->value);
    }

    public function isInt(): bool
    {
        return is_int($this->value);
    }

    public function isNumber(): bool
    {
        return $this->isInt() || $this->isFloat();
    }

    /**
     * @return bool
     * @alias self::isNumber()
     */
    public function isNumeric(): bool
    {
        return $this->isNumber();
    }

    public function mod(int|float $divisor): Arithmetic
    {
        return $this->arithmetic->mod($divisor);
    }

    public function multiply(int|float $factor): Arithmetic
    {
        return $this->arithmetic->multiply($factor);
    }

    public function negate(): Arithmetic
    {
        return $this->arithmetic->negate();
    }

    public function pow(int|float $exponent): Arithmetic
    {
        return $this->arithmetic->pow($exponent);
    }

    public function sqrt(int $exponent = 2): Arithmetic
    {
        return $this->arithmetic->sqrt($exponent);
    }

    public function subtract(int|float $amount = 1): Arithmetic
    {
        return $this->arithmetic->subtract($amount);
    }

    public function wordify(Locale|string|null $locale = null): string
    {
        $this->assertNumber();

        if (! is_finite($this->value)) {
            throw NumberFormattingException::mustBeFinite();
        }

        if ($this->value < self::MIN_SAFE_INTEGER || $this->value > self::MAX_SAFE_INTEGER) {
            throw NumberFormattingException::outOfRange(self::MIN_SAFE_INTEGER, self::MAX_SAFE_INTEGER);
        }

        $locale ??= self::getDefaultLocale() ?? 'en_US';
        $locale = $locale instanceof Locale ? $locale->value : $locale;

        if (trim($locale) === '') {
            throw new EmptyLocaleException();
        }

        return new Wordify($this->value, $locale)->format();
    }
}
