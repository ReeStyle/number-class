<?php

namespace Tests\Unit\Conversion;

use ReeStyleIt\NumberClass\Conversion\Time;
use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use ReeStyleIt\NumberClass\Exceptions\TimeException;

use function ReeStyleIt\NumberClass\number;
use function ReeStyleIt\NumberClass\time;

it('formats seconds with days when needed or explicitly requested', function (
    int|float $seconds,
    string $automatic,
    string $withDays,
) {
    $time = new Time($seconds);

    expect($time->asTime())->toBe($automatic)
        ->and($time->asTime(includeDays: true))->toBe($withDays)
        ->and($time->asTime(includeDays: false))->toBe($automatic)
        ->and($time->get())->toBe($seconds);
})->with([
    [0, '00:00:00', '00:00:00:00'],
    [59, '00:00:59', '00:00:00:59'],
    [60, '00:01:00', '00:00:01:00'],
    [3600, '01:00:00', '00:01:00:00'],
    [3661, '01:01:01', '00:01:01:01'],
    [86399, '23:59:59', '00:23:59:59'],
    [86400, '01:00:00:00', '01:00:00:00'],
    [90061, '01:01:01:01', '01:01:01:01'],
    [8640000, '100:00:00:00', '100:00:00:00'],
    [3661.99, '01:01:01', '00:01:01:01'],
    [0.99, '00:00:00', '00:00:00:00'],
    [PHP_INT_MAX, '106751991167300:15:30:07', '106751991167300:15:30:07'],
]);

it('formats through the helper and shared accessor', function () {
    expect(time(3661)->asTime())->toBe('01:01:01')
        ->and(number(3661)->time->asTime(includeDays: true))->toBe('00:01:01:01');
});

it('rejects negative or nonfinite durations for formatting', function (int|float $seconds) {
    expect(fn () => time($seconds)->asTime())->toThrow(TimeException::class);
})->with([-1, INF, -INF, NAN]);

it('rejects floats outside the supported whole-second range', function () {
    expect(fn () => time((float) PHP_INT_MAX)->asTime())
        ->toThrow(TimeException::class, 'Time is too large');
});

it('requires a numeric stored duration for formatting', function (mixed $seconds) {
    expect(fn () => time($seconds)->asTime())->toThrow(MustBeNumberException::class);
})->with([
    'string' => ['60'],
    'null' => [null],
    'boolean' => [true],
    'array' => [[]],
]);
