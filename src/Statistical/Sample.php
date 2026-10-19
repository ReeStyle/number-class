<?php

namespace ReeStyleIt\NumberClass\Statistical;

use ReeStyleIt\NumberClass\Exceptions\StatisticalException;

abstract readonly class Sample
{
    /** @var list<int|float> */
    public array $values;

    public function __construct(iterable $values)
    {
        $sample = [];

        foreach ($values as $value) {
            if ((! is_int($value) && ! is_float($value)) || ! is_finite($value)) {
                throw new StatisticalException();
            }

            $sample[] = $value;
        }

        if ($sample === []) {
            throw new StatisticalException();
        }

        sort($sample, SORT_REGULAR);
        $this->values = $sample;
    }

    /** @param non-empty-list<int|float> $values Sorted values. */
    protected static function middle(array $values): int|float
    {
        $count = count($values);
        $middle = intdiv($count, 2);

        if (($count % 2) === 1) {
            return $values[$middle];
        }

        $left = $values[($middle - 1)];
        $right = $values[$middle];

        if ($left === $right) {
            return $left;
        }

        // Avoid overflowing the sum of large values or the difference of opposite signs.
        return (($left < 0) && ($right >= 0))
            ? (($left + $right) / 2)
            : ($left + (($right - $left) / 2));
    }
}
