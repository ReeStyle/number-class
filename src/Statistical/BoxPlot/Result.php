<?php

namespace ReeStyleIt\NumberClass\Statistical\BoxPlot;

readonly class Result
{
    /** @param list<int|float> $outliers */
    public function __construct(
        /** Smallest sample value, including outliers. */
        public int|float $min,
        /** First quartile: median of the lower half, excluding an unpaired overall median. */
        public int|float $q1,
        /** Middle sample value, or the average of the two middle values. */
        public int|float $median,
        /** Third quartile: median of the upper half, excluding an unpaired overall median. */
        public int|float $q3,
        /** Largest sample value, including outliers. */
        public int|float $max,
        /** Interquartile range: q3 minus q1, the spread of the middle half. */
        public int|float $iqr,
        /** Smallest sample value at or above the lower fence (q1 - 1.5 * iqr). */
        public int|float $lowerWhisker,
        /** Largest sample value at or below the upper fence (q3 + 1.5 * iqr). */
        public int|float $upperWhisker,
        /** Values outside the fences, sorted ascending and retaining duplicates. */
        public array $outliers,
    ) {}
}
