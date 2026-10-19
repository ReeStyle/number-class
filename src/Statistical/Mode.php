<?php

namespace ReeStyleIt\NumberClass\Statistical;

readonly class Mode extends Sample
{
    /** @return list<int|float> */
    public function get(): array
    {
        $modes = [];
        $highest = 1;
        $count = count($this->values);

        for ($start = 0; $start < $count; $start = $end) {
            $value = $this->values[$start];
            $end = $start + 1;

            while ($end < $count && $this->values[$end] == $value) {
                $end++;
            }

            $frequency = $end - $start;

            if ($frequency > $highest) {
                $highest = $frequency;
                $modes = [$value];
            } elseif ($frequency === $highest && $highest > 1) {
                $modes[] = $value;
            }
        }

        return $modes;
    }
}
