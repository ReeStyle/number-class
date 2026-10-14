<?php

namespace ReeStyleIt\NumberClass\Format;

use InvalidArgumentException;
use ReeStyleIt\NumberClass\Base;
use RoundingMode;
use ValueError;

class Rounded extends Base
{
    public function with(int $precision = 0, int|RoundingMode $mode = RoundingMode::HalfAwayFromZero): self
    {
        $this->assertNumber();

        if (is_int($mode) && ($mode < 1 || $mode > count(RoundingMode::cases()))) {
            throw new ValueError('Rounding mode must be a valid RoundingMode case or its integer equivalent.');
        }

        return new self(round($this->value, $precision, $mode));
    }

    public function nearest(int|float $multiple): self
    {
        $this->assertNumber();

        if (!is_finite($multiple) || $multiple <= 0) {
            throw new InvalidArgumentException('Multiple must be a positive finite number.');
        }

        return new self(round($this->value / $multiple) * $multiple);
    }
}
