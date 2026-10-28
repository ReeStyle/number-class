<?php

namespace Tests\Unit\Financial;

use ReeStyleIt\NumberClass\Exceptions\FinancialException;
use ReeStyleIt\NumberClass\Financial\Amortization\Schedule\Line;
use ReeStyleIt\NumberClass\Financial\Amortization\Schedule;

use function ReeStyleIt\NumberClass\amortization;

it('provides array access, first and last rows, iteration, and counting', function () {
    $schedule = amortization(1200)->schedule(0, 12);

    expect($schedule)->toBeInstanceOf(Schedule::class)
        ->and($schedule[0])->toBeInstanceOf(Line::class)->not->toBe($schedule[1])
        ->and(count($schedule))->toBe(12)
        ->and($schedule->count())->toBe(12)
        ->and($schedule->isEmpty())->toBeFalse()
        ->and($schedule->first())->toBe($schedule[0])
        ->and($schedule->first)->toBe($schedule[0])
        ->and($schedule->last())->toBe($schedule[11])
        ->and($schedule->last)->toBe($schedule[11])
        ->and($schedule->get(3))->toBe($schedule[3])
        ->and(isset($schedule[0]))->toBeTrue()
        ->and(isset($schedule[12]))->toBeFalse()
        ->and($schedule[12])->toBeNull()
        ->and(iterator_to_array($schedule))->toBe($schedule->rows)
        ->and(json_encode($schedule, JSON_THROW_ON_ERROR))->toBe(json_encode($schedule->toArray(), JSON_THROW_ON_ERROR));
});

it('extracts schedule columns', function () {
    $schedule = amortization(1200)->schedule(0, 12);

    expect($schedule->column('period'))->toBe(range(1, 12))
        ->and($schedule->column('payment'))->toBe(array_fill(0, 12, 100.0));
});

it('handles an empty schedule consistently', function () {
    $empty = new Schedule([]);

    expect($empty->isEmpty())->toBeTrue()
        ->and(count($empty))->toBe(0)
        ->and($empty->first())->toBeNull()
        ->and($empty->last())->toBeNull()
        ->and($empty->get(0))->toBeNull()
        ->and(isset($empty[0]))->toBeFalse()
        ->and($empty[0])->toBeNull()
        ->and($empty->column('payment'))->toBe([])
        ->and($empty->toArray())->toBe([])
        ->and(iterator_to_array($empty))->toBe([])
        ->and(json_encode($empty))->toBe('[]');
});

it('protects the calculated schedule from mutation', function () {
    $schedule = amortization(1200)->schedule(0, 12);
    $rows = $schedule->toArray();
    $rows[0]['balance'] = -1;
    $iterator = $schedule->getIterator();
    $iterator[0] = [];

    expect($schedule[0]->balance)->toBe(1100.0);

    expect(function () use ($schedule) {
        $schedule[0] = [];
    })->toThrow(FinancialException::class);

    expect(function () use ($schedule) {
        $schedule[] = [];
    })->toThrow(FinancialException::class);

    expect(function () use ($schedule) {
        unset($schedule[0]);
    })->toThrow(FinancialException::class);

    expect(function () use ($schedule) {
        $schedule->rows = [];
    })->toThrow(\Error::class);

    expect(function () use ($schedule) {
        $schedule[0]->balance = -1;
    })->toThrow(\Error::class);
});

it('exports lines as plain arrays and preserves their JSON representation', function () {
    $schedule = amortization(1200)->schedule(0, 12);
    $line = $schedule->first();
    $expected = ['period' => 1, 'payment' => 100.0, 'interest' => 0.0, 'principal' => 100.0, 'balance' => 1100.0];

    expect($line->toArray())->toBe($expected)
        ->and($schedule->toArray()[0])->toBe($expected)
        ->and(json_encode($line, JSON_THROW_ON_ERROR))->toBe(json_encode($expected, JSON_THROW_ON_ERROR))
        ->and(array_column($schedule->toArray(), 'period'))->toBe(range(1, 12));
});

it('rejects schedule entries that are not Line objects', function () {
    expect(fn () => new Schedule([['period' => 1]]))->toThrow(FinancialException::class);
});
