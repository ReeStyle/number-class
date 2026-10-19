<?php

namespace ReeStyleIt\NumberClass\Mathematical\Dimension;

readonly class Cube extends Cuboid
{
    public function __construct(public int|float $side)
    {
        parent::__construct($side, $side, $side);
    }
}
