<?php

namespace ReeStyleIt\NumberClass\Mathmatical;

use InvalidArgumentException;
use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Number;

class Pythagoras extends Base
{
    /**
     * Treat the stored value and $otherLeg as the two perpendicular sides.
     */
    public function hypotenuse(int|float $otherLeg): self
    {
        $this->assertNumber();
        $this->assertLength($this->value);
        $this->assertLength($otherLeg);

        $scale = max($this->value, $otherLeg);

        if ($scale == 0) {
            return new self(0.0);
        }

        // Normalize the sides before squaring to avoid overflow or underflow.
        $length = new Number(min($this->value, $otherLeg))
            ->divide($scale)
            ->pow(2)
            ->increase(1)
            ->sqrt()
            ->multiply($scale);

        return new self($length->get());
    }

    /**
     * Treat the stored value as the hypotenuse and calculate the missing side.
     */
    public function leg(int|float $otherLeg): self
    {
        $this->assertNumber();
        $this->assertLength($this->value);
        $this->assertLength($otherLeg);

        if ($otherLeg > $this->value) {
            throw new InvalidArgumentException('A leg cannot be longer than the hypotenuse.');
        }

        if ($this->value == 0) {
            return new self(0.0);
        }

        // Scale before squaring to avoid overflow for large lengths.
        $sumRatio = new Number($otherLeg)->divide($this->value)->increase(1);
        $length = new Number($this->value)
            ->decrease($otherLeg)
            ->divide($this->value)
            ->multiply($sumRatio->get())
            ->sqrt()
            ->multiply($this->value);

        return new self($length->get());
    }

    private function assertLength(int|float $length): void
    {
        if (!is_finite($length) || $length < 0) {
            throw new InvalidArgumentException('Length must be a non-negative finite number.');
        }
    }
}
