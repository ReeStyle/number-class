<?php

namespace ReeStyleIt\NumberClass;

use ReeStyleIt\NumberClass\Mathematical\Arithmetic;

class Number extends Base
{
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
}
