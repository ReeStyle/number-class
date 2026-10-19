<?php

namespace ReeStyleIt\NumberClass\Mathematical\Dimension;

use ReeStyleIt\NumberClass\Number;

/** A right pyramid whose apex is above the center of a rectangular base. */
readonly class Pyramid extends SolidShape
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
        $lengthFaces = $this->length * hypot($this->height, $this->width / 2);
        $widthFaces = $this->width * hypot($this->height, $this->length / 2);

        return $this->result((($this->length * $this->width) + $lengthFaces) + $widthFaces);
    }

    public function volume(): Number
    {
        if (($this->length == 0) || ($this->width == 0) || ($this->height == 0)) {
            return $this->result(0.0);
        }

        return $this->result(($this->length * $this->width) * ($this->height / 3));
    }
}
