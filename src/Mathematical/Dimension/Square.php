<?php

namespace ReeStyleIt\NumberClass\Mathematical\Dimension;

readonly class Square extends Rectangle
{
    public function __construct(public int|float $side)
    {
        parent::__construct($side, $side);
    }
}
