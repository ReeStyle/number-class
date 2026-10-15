<?php

namespace ReeStyleIt\NumberClass\Format;

use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Exceptions\MustBePositiveNumberException;
use ReeStyleIt\NumberClass\Exceptions\RoundingException;
use RoundingMode;

class Round extends Base
{
    public function decimals(int $precision = 0, int|RoundingMode $mode = RoundingMode::HalfAwayFromZero): self
    {
        $this->assertNumber();

        if (is_int($mode) && ($mode < 1 || $mode > count(RoundingMode::cases()))) {
            throw new RoundingException();
        }

        return new self(round($this->value, $precision, $mode));
    }

    public function nearest(int|float $multiple): self
    {
        $this->assertNumber();

        if (!is_finite($multiple) || $multiple <= 0) {
            throw MustBePositiveNumberException::multiple();
        }

        return new self(round($this->value / $multiple) * $multiple);
    }

    public function with(int $precision = 0, int|RoundingMode $mode = RoundingMode::HalfAwayFromZero): self
    {
        return $this->decimals($precision, $mode);
    }
}
