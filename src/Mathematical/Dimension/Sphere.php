<?php

namespace ReeStyleIt\NumberClass\Mathematical\Dimension;

use ReeStyleIt\NumberClass\Number;

readonly class Sphere extends SolidShape
{
    public function __construct(public int|float $radius)
    {
        self::assertLengths($radius);
    }

    public function surface(): Number
    {
        return $this->result((4 * M_PI) * ($this->radius * $this->radius));
    }

    public function volume(): Number
    {
        return $this->result(((4 * M_PI) / 3) * (($this->radius * $this->radius) * $this->radius));
    }
}
