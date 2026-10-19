<?php

namespace ReeStyleIt\NumberClass\Statistical;

readonly class Median extends Sample
{
    public function get(): int|float
    {
        return self::middle($this->values);
    }
}
