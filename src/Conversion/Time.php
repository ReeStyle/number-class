<?php

namespace ReeStyleIt\NumberClass\Conversion;

use Carbon\Carbon;
use DateInterval;
use DateTimeInterface;
use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Conversion\Time\Diff;
use ReeStyleIt\NumberClass\Exceptions\TimeException;

class Time extends Base
{
    public Diff $from {
        get {
            return $this->from($this->value);
        }
    }

    public private(set) array $results = [];

    public function __construct(mixed $value = 0)
    {
        parent::__construct($value);
    }

    public function asTime(bool $includeDays = false): string
    {
        $this->assertNumber();

        if (!is_finite($this->value) || $this->value < 0) {
            throw new TimeException();
        }

        if (is_float($this->value) && $this->value >= PHP_INT_MAX) {
            throw TimeException::tooLarge();
        }

        // Preferring native functions over library methods for performance reasons
        $seconds = (int) $this->value;
        $days = intdiv($seconds, 86400);
        $hours = intdiv($seconds % 86400, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $notation = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds % 60);

        return ($includeDays || $days > 0) ? sprintf('%02d:%s', $days, $notation) : $notation;
    }

    public function from(
        DateTimeInterface|Carbon|int|string $from,
        DateTimeInterface|Carbon|int|string|null $to = null,
    ): Diff|DateInterval {
        $difference = new Diff($from);

        return func_num_args() > 1 ? $difference->diff($to) : $difference;
    }
}
