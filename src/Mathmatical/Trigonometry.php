<?php

namespace ReeStyleIt\NumberClass\Mathmatical;

use ReeStyleIt\NumberClass\Base;

/**
 * Trigonometric angles are expressed in radians.
 */
class Trigonometry extends Base
{
    public function sin(): self
    {
        $this->assertNumber();

        return new self(sin($this->value));
    }

    public function cos(): self
    {
        $this->assertNumber();

        return new self(cos($this->value));
    }

    public function tan(): self
    {
        $this->assertNumber();

        return new self(tan($this->value));
    }

    public function asin(): self
    {
        $this->assertNumber();

        return new self(asin($this->value));
    }

    public function acos(): self
    {
        $this->assertNumber();

        return new self(acos($this->value));
    }

    public function atan(): self
    {
        $this->assertNumber();

        return new self(atan($this->value));
    }

    public function atan2(int|float $x): self
    {
        $this->assertNumber();

        return new self(atan2($this->value, $x));
    }

    public function deg2rad(): self
    {
        $this->assertNumber();

        return new self(deg2rad($this->value));
    }

    public function rad2deg(): self
    {
        $this->assertNumber();

        return new self(rad2deg($this->value));
    }
}
