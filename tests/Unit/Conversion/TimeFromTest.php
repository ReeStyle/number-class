<?php

namespace Tests\Unit\Conversion;

use Carbon\Carbon;
use DateTime;
use DateTimeImmutable;
use ReeStyleIt\NumberClass\Conversion\Time;
use ReeStyleIt\NumberClass\Conversion\Time\Diff;
use ReeStyleIt\NumberClass\Exceptions\TimeException;

it('stores a starting point as Carbon and supports chaining', function (mixed $from, int $expected) {
    $time = new Time(60);

    $difference = $time->from($from);

    expect($difference)->toBeInstanceOf(Diff::class)
        ->and($time->from($from))->not->toBe($difference)
        ->and($difference->from)->toBeInstanceOf(Carbon::class)
        ->and($difference->from->getTimestamp())->toBe($expected)
        ->and($time->get())->toBe(60);
})->with([
    'epoch'                     => [0, 0],
    'before epoch'              => [-1, -1],
    'maximum timestamp'         => [PHP_INT_MAX, PHP_INT_MAX],
    'minimum timestamp'         => [PHP_INT_MIN, PHP_INT_MIN],
    'maximum timestamp string'  => [(string) PHP_INT_MAX, PHP_INT_MAX],
    'minimum timestamp string'  => [(string) PHP_INT_MIN, PHP_INT_MIN],
    'integer'                   => [1729166400, 1729166400],
    'timestamp string'          => ['1729166400', 1729166400],
    'epoch string'              => ['0', 0],
    'positive timestamp string' => ['+001234', 1234],
    'native timestamp string'   => ['@1234', 1234],
    'negative timestamp string' => ['-1', -1],
    'date string with timezone' => ['2024-10-17T14:00:00+02:00', 1729166400],
    'mutable date'              => [new DateTime('2024-10-17T12:00:00+00:00'), 1729166400],
    'immutable date'            => [new DateTimeImmutable('2024-10-17T12:00:00+00:00'), 1729166400],
]);

it('rejects invalid starting points without changing the previous timestamp', function (string $from) {
    $time = new Time(60)->from(0);

    expect(fn () => new Time()->from($from))->toThrow(TimeException::class)
        ->and($time->from->getTimestamp())->toBe(0);
})->with(['', '   ', 'not a date', '2026-02-30', "2026-01-01\0", '999999999999999999999999999']);

it('captures a date timestamp independently of later changes to the date', function () {
    $date = new DateTime('@0');
    $time = new Time(60)->from($date);
    $date->modify('+1 day');

    expect($time->from->getTimestamp())->toBe(0)
        ->and(new Time()->from(-1)->from->getTimestamp())->toBe(-1);
});

it('copies Carbon inputs while preserving their timezone and microseconds', function () {
    $date = new Carbon('2024-10-17T14:00:00.123456+02:00');
    $time = new Time()->from($date);

    expect($time->from)->not->toBe($date)
        ->and($time->from->format('Y-m-d H:i:s.uP'))->toBe('2024-10-17 14:00:00.123456+02:00');

    $date->addDay();

    expect($time->from->getTimestamp())->toBe(1729166400);
});

it('protects the normalized starting point from direct writes', function () {
    $time = new Time(60)->from(0);

    expect(function () use ($time) {
        $time->from = 100;
    })->toThrow(\Error::class);
});

it('uses the default timezone for date strings without an offset', function () {
    $timezone = date_default_timezone_get();

    try {
        date_default_timezone_set('Europe/Amsterdam');
        $time = new Time()->from('2024-10-17 14:00:00');

        expect($time->from->getTimestamp())->toBe(1729166400);
    } finally {
        date_default_timezone_set($timezone);
    }
});

it('preserves the native parser exception as the cause', function () {
    try {
        new Time()->from('not a date');
    } catch (TimeException $error) {
        expect($error->getMessage())->toBe('Starting point must be a valid Unix timestamp or date.')
            ->and($error->getPrevious())->toBeInstanceOf(\DateMalformedStringException::class);

        return;
    }

    $this->fail('Expected an invalid starting point exception.');
});
