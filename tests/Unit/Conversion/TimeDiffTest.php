<?php

namespace Tests\Unit\Conversion;

use Carbon\Carbon;
use DateTimeImmutable;
use DateInterval;
use ReeStyleIt\NumberClass\Conversion\Time;
use ReeStyleIt\NumberClass\Exceptions\TimeException;

it('returns a native DateInterval with direction', function (mixed $end, float $expected) {
    $source = new Time(42);
    $time = $source->from(0);
    $result = $time->diff($end);

    expect($result)->toBeInstanceOf(DateInterval::class)
        ->and(get_class($result))->toBe(DateInterval::class)
        ->and($result->i * 60 + $result->s)->toBe((int) abs($expected))
        ->and($result->invert)->toBe($expected < 0 ? 1 : 0)
        ->and($time->from->getTimestamp())->toBe(0)
        ->and($source->get())->toBe(42)
        ->and($source->results)->toBe([]);
})->with([
    'same instant' => [0, 0.0],
    'later timestamp' => [60, 60.0],
    'earlier timestamp' => [-60, -60.0],
    'timestamp string' => ['60', 60.0],
    'date string' => ['1970-01-01T01:01:00+01:00', 60.0],
    'date object' => [new DateTimeImmutable('@60'), 60.0],
    'Carbon' => [new Carbon('@60'), 60.0],
]);

it('preserves fractional seconds and compares instants across a daylight saving change', function () {
    $time = new Time()->from('2024-03-31T01:30:00.123456+01:00');

    expect($time->diff('2024-03-31T03:30:00.623456+02:00')->format('%h:%i:%s.%F'))->toBe('1:0:0.500000');

    $earlier = new Time()->from('1970-01-01T00:00:00.500000Z')->diff(0);

    expect($earlier->invert)->toBe(1)
        ->and($earlier->s)->toBe(0)
        ->and($earlier->f)->toBe(0.5);
});

it('defaults to the current time and recalculates on subsequent calls', function () {
    Carbon::setTestNow(new Carbon('@60'));

    try {
        $time = new Time()->from(0);

        expect($time->diff()->i)->toBe(1);

        Carbon::setTestNow(new Carbon('@120'));

        expect($time->diff()->i)->toBe(2)
            ->and(new Time()->from(60)->diff()->i)->toBe(1);
    } finally {
        Carbon::setTestNow();
    }
});

it('calculates differences through the starting point property', function () {
    expect(new Time(60)->from->diff(90)->s)->toBe(30);
});

it('rejects invalid endpoints without changing the starting point', function (string $end) {
    $time = new Time()->from(0);

    expect(fn () => $time->diff($end))->toThrow(TimeException::class, 'Ending point')
        ->and($time->from->getTimestamp())->toBe(0);
})->with(['', 'not a date', '2026-02-30']);

it('returns independent DateInterval results', function () {
    $time = new Time()->from(0);
    $result = $time->diff(60);
    $result->d = 99;

    expect($time->diff(60)->d)->toBe(0)
        ->and($time->from->getTimestamp())->toBe(0);
});

it('exposes calendar components and total days', function () {
    $interval = new Time()->from('2024-01-01T00:00:00Z')->diff('2025-03-04T05:06:07Z');

    expect([$interval->y, $interval->m, $interval->d, $interval->h, $interval->i, $interval->s])
        ->toBe([1, 2, 3, 5, 6, 7])
        ->and($interval->days)->toBe(428);
});
