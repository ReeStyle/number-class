<?php

namespace ReeStyleIt\NumberClass\Statistical;

use ReeStyleIt\NumberClass\Exceptions\StatisticalException;
use ReeStyleIt\NumberClass\Statistical\BoxPlot\Result;

readonly class BoxPlot extends Sample
{
    private Result $result;
    public int|float $min;
    public int|float $q1;
    public int|float $median;
    public int|float $q3;
    public int|float $max;
    public int|float $iqr;
    public int|float $lowerWhisker;
    public int|float $upperWhisker;
    /** @var list<int|float> */
    public array $outliers;

    public function __construct(iterable $values)
    {
        // This sorts the values from lowest to highest
        parent::__construct($values);

        $count = count($this->values);
        $half = intdiv($count, 2);

        $this->min = $this->values[0];
        $this->max = $this->values[$count - 1];
        $this->median = self::middle($this->values);
        $this->q1 = $count === 1 ? $this->median : self::middle(array_slice($this->values, 0, $half));
        $this->q3 = $count === 1 ? $this->median : self::middle(array_slice($this->values, $half + $count % 2));
        $this->iqr = $this->q3 - $this->q1;

        $lowerFence = $this->q1 - 1.5 * $this->iqr;
        $upperFence = $this->q3 + 1.5 * $this->iqr;

        if (! is_finite($lowerFence) || ! is_finite($upperFence)) {
            throw new StatisticalException('Box plot range exceeds the supported numeric range.');
        }

        $outliers = [];
        $lower = null;
        $upper = null;

        foreach ($this->values as $value) {
            if ($value < $lowerFence || $value > $upperFence) {
                $outliers[] = $value;
            } else {
                $lower ??= $value;
                $upper = $value;
            }
        }

        $this->lowerWhisker = $lower;
        $this->upperWhisker = $upper;
        $this->outliers = $outliers;
        $this->result = new Result(
            min: $this->min,
            q1: $this->q1,
            median: $this->median,
            q3: $this->q3,
            max: $this->max,
            iqr: $this->iqr,
            lowerWhisker: $this->lowerWhisker,
            upperWhisker: $this->upperWhisker,
            outliers: $this->outliers,
        );
    }

    public function get(): Result
    {
        return $this->result;
    }
}
