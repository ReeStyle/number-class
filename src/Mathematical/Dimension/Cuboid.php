<?php

namespace ReeStyleIt\NumberClass\Mathematical\Dimension;

use ReeStyleIt\NumberClass\Number;

readonly class Cuboid extends SolidShape
{
    public function __construct(
        public int|float $length,
        public int|float $width,
        public int|float $height,
    ) {
        self::assertLengths($length, $width, $height);
    }

    public function surface(): Number
    {
        return $this->result(2 * ((($this->length * $this->width) + ($this->length * $this->height))
            + ($this->width * $this->height)));
    }

    public function volume(): Number
    {
        if (($this->length == 0) || ($this->width == 0) || ($this->height == 0)) {
            return $this->result(0.0);
        }

        return $this->result(($this->length * $this->width) * $this->height);
    }

    public function diagonal(): Number
    {
        return $this->result(hypot(hypot($this->length, $this->width), $this->height));
    }
}
