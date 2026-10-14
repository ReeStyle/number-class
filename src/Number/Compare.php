<?php

namespace ReeStyleIt\NumberClass\Number;

use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Number;

class Compare extends Base
{
    public function equals(int|float|Number $other): bool
    {
        $other = $this->comparisonValue($other);

        return $this->value == $other;
    }

    public function higherThan(int|float|Number $other): bool
    {
        $other = $this->comparisonValue($other);

        return $this->value > $other;
    }

    public function lowerThan(int|float|Number $other): bool
    {
        $other = $this->comparisonValue($other);

        return $this->value < $other;
    }

    public function higherThanOrEqual(int|float|Number $other): bool
    {
        $other = $this->comparisonValue($other);

        return $this->value >= $other;
    }

    public function lowerThanOrEqual(int|float|Number $other): bool
    {
        $other = $this->comparisonValue($other);

        return $this->value <= $other;
    }

    private function comparisonValue(int|float|Number $other): int|float
    {
        $this->assertNumber();

        if ($other instanceof Number) {
            $other->assertNumber();

            return $other->get();
        }

        return $other;
    }
}
