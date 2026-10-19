<?php

namespace ReeStyleIt\NumberClass\Number;

use ReeStyleIt\NumberClass\Base;

class Compare extends Base
{
    public function equals(int|float|Base $other, bool $strict = false): bool
    {
        $other = $this->comparisonValue($other);

        return $strict ? $this->value === $other : $this->value == $other;
    }

    public function higherThan(int|float|Base $other): bool
    {
        $other = $this->comparisonValue($other);

        return $this->value > $other;
    }

    public function lowerThan(int|float|Base $other): bool
    {
        $other = $this->comparisonValue($other);

        return $this->value < $other;
    }

    public function higherThanOrEqual(int|float|Base $other): bool
    {
        $other = $this->comparisonValue($other);

        return $this->value >= $other;
    }

    public function lowerThanOrEqual(int|float|Base $other): bool
    {
        $other = $this->comparisonValue($other);

        return $this->value <= $other;
    }

    private function comparisonValue(int|float|Base $other): int|float
    {
        $this->assertNumber();

        if ($other instanceof Base) {
            $other->assertNumber();

            return $other->get();
        }

        return $other;
    }
}
