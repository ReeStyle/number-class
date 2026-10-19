<?php

namespace ReeStyleIt\NumberClass\Mathematical\Dimension;

use ReeStyleIt\NumberClass\Number;

/** A right circular cone, including its base in the surface area. */
readonly class Cone extends SolidShape
{
    public function __construct(public int|float $radius, public int|float $height)
    {
        self::assertLengths($radius, $height);
    }

    public function slantHeight(): Number
    {
        return $this->result(hypot($this->radius, $this->height));
    }

    public function surface(): Number
    {
        return $this->result((M_PI * $this->radius) * ($this->radius + $this->slantHeight()->get()));
    }

    public function volume(): Number
    {
        if (($this->radius == 0) || ($this->height == 0)) {
            return $this->result(0.0);
        }

        return $this->result((M_PI * ($this->radius * $this->radius)) * ($this->height / 3));
    }
}
