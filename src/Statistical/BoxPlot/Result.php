<?php

namespace ReeStyleIt\NumberClass\Statistical\BoxPlot;

readonly class Result
{
    /** @param list<int|float> $outliers */
    public function __construct(
        public int|float $min,
        public int|float $q1,
        public int|float $median,
        public int|float $q3,
        public int|float $max,
        public int|float $iqr,
        public int|float $lowerWhisker,
        public int|float $upperWhisker,
        public array $outliers,
    ) {}
}
