<?php

namespace Tests\Unit\Conversion;

use Carbon\Carbon;
use DateInterval;
use DateTimeImmutable;
use ReeStyleIt\NumberClass\Conversion\Time;
use ReeStyleIt\NumberClass\Conversion\Time\Diff;
use ReeStyleIt\NumberClass\Exceptions\TimeException;

use function ReeStyleIt\NumberClass\number;
use function ReeStyleIt\NumberClass\time;

it('creates fresh difference objects from the stored value', function () {
    $time = new Time;

    expect($time->from)->toBeInstanceOf(Diff::class)
        ->and($time->from->from->getTimestamp())->toBe(0)
        ->and($time->from)->not->toBe($time->from)
        ->and($time->results)->toBe([])
        ->and($time->get())->toBe(0);
});

it('provides starting points through the helper and shared accessor', function () {
    expect(time()->from(0)->from->getTimestamp())->toBe(0)
        ->and(number(60)->time->from(-1)->from->getTimestamp())->toBe(-1)
        ->and(time(60)->number->get())->toBe(60);
});

it('protects results from external changes', function (\Closure $change) {
    $time = new Time;

    expect(fn () => $change($time))->toThrow(\Error::class)
        ->and($time->results)->toBe([]);
})->with([
    'replace' => [function (Time $time) {
        $time->results = ['hours' => 1];
    }],
    'append' => [function (Time $time) {
        $time->results[] = 1;
    }],
    'reference' => [function (Time $time) {
        $reference = &$time->results;
    }],
]);

it('keeps starting points independent of each other', function () {
    $time = new Time(60);
    $first = $time->from(0);
    $second = $time->from(120);

    expect($first)->not->toBe($second)
        ->and($first->from->getTimestamp())->toBe(0)
        ->and($second->from->getTimestamp())->toBe(120)
        ->and($time->from->from->getTimestamp())->toBe(60);
});

it('calculates an interval directly when an endpoint is supplied', function (mixed $to) {
    $time = new Time(42);
    $interval = $time->from(from: 60, to: $to);
    $expected = $time->from(60)->diff($to);

    expect($interval)->toBeInstanceOf(DateInterval::class)
        ->and($interval)->toEqual($expected)
        ->and($time->get())->toBe(42);
})->with([
    'zero timestamp' => [0],
    'zero timestamp string' => ['0'],
    'timestamp' => [90],
    'timestamp string' => ['90'],
    'date string' => ['1970-01-01T00:01:30Z'],
    'date object' => [new DateTimeImmutable('@90')],
    'Carbon' => [new Carbon('@90')],
]);

it('uses now when an explicit null endpoint is supplied', function () {
    Carbon::setTestNow(new Carbon('@90'));

    try {
        expect(new Time()->from(60))->toBeInstanceOf(Diff::class)
            ->and(new Time()->from(from: 60, to: null)->s)->toBe(30);
    } finally {
        Carbon::setTestNow();
    }
});

it('validates endpoints through the difference calculation', function () {
    expect(fn () => new Time()->from(0, 'not a date'))
        ->toThrow(TimeException::class, 'Ending point');
});
