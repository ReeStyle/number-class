<?php

namespace ReeStyleIt\NumberClass\Mathematical;

use DivisionByZeroError;
use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Exceptions\MustBePositiveNumberException;

class Arithmetic extends Base
{
    public function increase(int|float $amount = 1): self
    {
        $this->assertNumber();

        return new self($this->value + $amount);
    }

    public function decrease(int|float $amount = 1): self
    {
        $this->assertNumber();

        return new self($this->value - $amount);
    }

    public function multiply(int|float $factor): self
    {
        $this->assertNumber();

        return new self($this->value * $factor);
    }

    public function divide(int|float $divisor): self
    {
        $this->assertNumber();

        if ($divisor == 0) {
            throw new DivisionByZeroError;
        }

        return new self($this->value / $divisor);
    }

    public function pow(int|float $exponent): self
    {
        $this->assertNumber();

        return new self($this->value ** $exponent);
    }

    public function sqrt(int $exponent = 2): self
    {
        $this->assertNumber();

        if ($exponent <= 0) {
            throw MustBePositiveNumberException::rootExponent();
        }

        if ($exponent === 2) {
            return new self(sqrt($this->value));
        }

        if ($this->value < 0 && $exponent % 2 === 1) {
            return new self(-((-$this->value) ** (1 / $exponent)));
        }

        return new self($this->value ** (1 / $exponent));
    }

    public function abs(): self
    {
        $this->assertNumber();

        return new self(abs($this->value));
    }

    public function negate(): self
    {
        $this->assertNumber();

        return new self(-$this->value);
    }

    public function mod(int|float $divisor): self
    {
        $this->assertNumber();

        if ($divisor == 0) {
            throw new DivisionByZeroError('Cannot calculate a remainder with a zero divisor.');
        }

        if (is_int($this->value) && is_int($divisor)) {
            return new self($this->value % $divisor);
        }

        return new self(fmod($this->value, $divisor));
    }

    public function floor(): self
    {
        $this->assertNumber();

        return new self(floor($this->value));
    }

    public function ceil(): self
    {
        $this->assertNumber();

        return new self(ceil($this->value));
    }
}
