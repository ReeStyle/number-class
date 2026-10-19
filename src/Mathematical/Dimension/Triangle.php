<?php

namespace ReeStyleIt\NumberClass\Mathematical\Dimension;

use ReeStyleIt\NumberClass\Exceptions\GeometryException;
use ReeStyleIt\NumberClass\Number;

readonly class Triangle extends PlaneShape
{
    public function __construct(
        public int|float $sideA,
        public int|float $sideB,
        public int|float $sideC,
    ) {
        self::assertLengths($sideA, $sideB, $sideC);
        $sides = [$sideA, $sideB, $sideC];
        rsort($sides, SORT_REGULAR);

        if ($sides[2] < ($sides[0] - $sides[1])) {
            throw new GeometryException('Triangle sides must satisfy the triangle inequality.');
        }
    }

    public function area(): Number
    {
        $sides = [$this->sideA, $this->sideB, $this->sideC];
        rsort($sides, SORT_REGULAR);
        [$a, $b, $c] = $sides;

        if ($c == 0) {
            return $this->result(0.0);
        }

        // Factor Heron's formula by the largest and smallest sides, avoiding
        // underflow in very thin triangles and overflow in intermediate products.
        $difference = $a - $b;
        $first = 1 + (($b / $a) + ($c / $a));
        $second = ($c - $difference) / $c;
        $third = 1 + ($difference / $c);
        $fourth = 1 + (($b - $c) / $a);
        $factor = sqrt(max(0.0, (($first * $second) * $third) * $fourth)) / 4;

        return $this->result(($factor * $a) * $c);
    }

    public function perimeter(): Number
    {
        return $this->result(($this->sideA + $this->sideB) + $this->sideC);
    }
}
