<?php

namespace ReeStyleIt\NumberClass\Conversion\Time;

use Carbon\Carbon;
use DateInterval;
use DateMalformedStringException;
use DateRangeError;
use DateTimeImmutable;
use DateTimeInterface;
use ReeStyleIt\NumberClass\Exceptions\TimeException;
use ValueError;

class Diff
{
    public private(set) Carbon $from;

    public function __construct(DateTimeInterface|Carbon|int|string $from)
    {
        try {
            $date = match (true) {
                is_int($from)                      => $this->parseStartingPoint((string) $from),
                $from instanceof DateTimeInterface => Carbon::instance($from),
                default                            => $this->parseStartingPoint($from),
            };
        } catch (DateMalformedStringException|DateRangeError|ValueError $exception) {
            throw TimeException::invalidStartingPoint($exception);
        }

        $this->from = $date;
    }

    public function diff(DateTimeInterface|Carbon|int|string|null $to = null): DateInterval
    {
        try {
            $end = $to === null ? Carbon::now($this->from->getTimezone()) : new self($to)->from;
        } catch (TimeException $exception) {
            throw new TimeException('Ending point must be a valid Unix timestamp or date.', previous: $exception);
        }

        return DateTimeImmutable::createFromInterface($this->from)->diff($end);
    }

    private function parseStartingPoint(string $from): Carbon
    {
        if (str_contains($from, "\0")) {
            throw TimeException::invalidStartingPoint();
        }

        $from = trim($from);

        if ($from === '') {
            throw TimeException::invalidStartingPoint();
        }

        if (preg_match('/^[+-]?[0-9]+$/D', $from)) {
            $from = '@' . ltrim($from, '+');
        }

        $date = new DateTimeImmutable($from);
        $errors = DateTimeImmutable::getLastErrors();

        if ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) {
            throw TimeException::invalidStartingPoint();
        }

        $date->getTimestamp();

        return Carbon::instance($date);
    }
}
