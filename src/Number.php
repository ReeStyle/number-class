<?php

namespace ReeStyleIt\NumberClass;

use ReeStyleIt\NumberClass\Format\Currency;
use ReeStyleIt\NumberClass\Format\Rounded;
use ReeStyleIt\NumberClass\Mathematical\Arithmetic;
use ReeStyleIt\NumberClass\Mathematical\Pythagoras;
use ReeStyleIt\NumberClass\Mathematical\Trigonometry;
use ReeStyleIt\NumberClass\Number\Compare;

class Number extends Base
{
    public Compare $compare {
        get => $this->compare();
    }

    public Rounded $round {
        get => $this->round();
    }

    public function isInt(): bool
    {
        return is_int($this->value);
    }

    public function isFloat(): bool
    {
        return is_float($this->value);
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

    public function compare(): Compare
    {
        $this->assertNumber();

        return new Compare($this->value);
    }

    public function arithmetic(): Arithmetic
    {
        $this->assertNumber();

        return new Arithmetic($this->value);
    }

    public function trigonometry(): Trigonometry
    {
        $this->assertNumber();

        return new Trigonometry($this->value);
    }

    public function pythagoras(): Pythagoras
    {
        $this->assertNumber();

        return new Pythagoras($this->value);
    }

    public function increase(int|float $amount = 1): self
    {
        return new self($this->arithmetic()->increase($amount)->get());
    }

    public function decrease(int|float $amount = 1): self
    {
        return new self($this->arithmetic()->decrease($amount)->get());
    }

    public function multiply(int|float $factor): self
    {
        return new self($this->arithmetic()->multiply($factor)->get());
    }

    public function divide(int|float $divisor): self
    {
        return new self($this->arithmetic()->divide($divisor)->get());
    }

    public function pow(int|float $exponent): self
    {
        return new self($this->arithmetic()->pow($exponent)->get());
    }

    public function sqrt(int $exponent = 2): self
    {
        return new self($this->arithmetic()->sqrt($exponent)->get());
    }

    public function abs(): self
    {
        return new self($this->arithmetic()->abs()->get());
    }

    public function negate(): self
    {
        return new self($this->arithmetic()->negate()->get());
    }

    public function mod(int|float $divisor): self
    {
        return new self($this->arithmetic()->mod($divisor)->get());
    }

    public function floor(): self
    {
        return new self($this->arithmetic()->floor()->get());
    }

    public function ceil(): self
    {
        return new self($this->arithmetic()->ceil()->get());
    }

    public function round(): Rounded
    {
        $this->assertNumber();

        return new Rounded($this->value);
    }

    public function currency(): Currency
    {
        $this->assertNumber();

        return new Currency($this->value);
    }

    public function format(int $decimals = 0, ?string $decimal_separator = '.', ?string $thousands_separator = ','): string
    {
        $this->assertNumber();

        return number_format($this->value, $decimals, $decimal_separator, $thousands_separator);
    }
}
