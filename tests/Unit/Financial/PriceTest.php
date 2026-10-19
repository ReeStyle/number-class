<?php

namespace Tests\Unit\Financial;

use DivisionByZeroError;
use ReeStyleIt\NumberClass\Exceptions\FinancialException;
use ReeStyleIt\NumberClass\Financial\Price;
use RoundingMode;

use function ReeStyleIt\NumberClass\price;

it('calculates exact decimal prices without changing the source', function () {
    $price = price('0.10');

    expect($price)->toBeInstanceOf(Price::class)
        ->and($price->add('0.20')->get())->toBe('0.30')
        ->and($price->subtract('0.20')->get())->toBe('-0.10')
        ->and($price->multiply('0.20')->get())->toBe('0.0200')
        ->and($price->divide(2)->format())->toBe('0.05')
        ->and($price->add(price('0.20'))->format())->toBe('0.30')
        ->and($price->get())->toBe('0.10')
        ->and((string) $price)->toBe('0.10');
});

it('preserves large amounts and fractional precision until explicit rounding', function () {
    expect(price('99999999999999999999.99')->add('0.01')->get())->toBe('100000000000000000000.00')
        ->and(price('0.004')->multiply(3)->get())->toBe('0.012')
        ->and(price('0.004')->multiply(3)->format())->toBe('0.01');
});

it('handles percentages, discounts, and tax without float conversion', function () {
    $price = price('19.95');

    expect($price->percentage(15)->get())->toBe('2.9925')
        ->and($price->discount(15)->format())->toBe('16.96')
        ->and($price->withTax(21)->format())->toBe('24.14')
        ->and(price('121')->withoutTax(21)->format())->toBe('100.00')
        ->and($price->withTax('21.5')->withoutTax('21.5')->format())->toBe('19.95')
        ->and($price->discount(100)->format())->toBe('0.00')
        ->and($price->withTax(0)->format())->toBe('19.95')
        ->and(price('-100')->withTax(21)->format())->toBe('-121.00');
});

it('supports decimal rounding modes and fixed decimal output', function () {
    expect(price('1.005')->format())->toBe('1.01')
        ->and(price('1.005')->format(mode: RoundingMode::HalfEven))->toBe('1.00')
        ->and(price('-1.005')->format())->toBe('-1.01')
        ->and(price('12.5')->round(0)->get())->toBe('13')
        ->and(price('125')->round(-1)->get())->toBe('130')
        ->and(price(12)->format(3))->toBe('12.000')
        ->and(price('-0.000')->format())->toBe('0.00');
});

it('uses explicit division scales and preserves the global BCMath scale', function () {
    $previous = bcscale(0);

    try {
        expect(price('0.10')->add('0.20')->get())->toBe('0.30')
            ->and(price(1)->divide(3)->get())->toBe('0.333333333333')
            ->and(price(1)->divide(3, scale: 4)->get())->toBe('0.3333')
            ->and(price(1)->divide(6)->format())->toBe('0.17')
            ->and(bcscale())->toBe(0);
    } finally {
        bcscale($previous);
    }
});

it('normalizes supported decimal values', function (mixed $input, string $expected) {
    expect(price($input)->get())->toBe($expected);
})->with([
    [15, '15'], ['+001.20', '1.20'], ['.5', '0.5'], ['1.', '1'], [new Price('1.20'), '1.20'],
]);

it('rejects inputs that would silently lose decimal precision', function (mixed $value) {
    expect(fn () => price($value))->toThrow(FinancialException::class)
        ->and(fn () => price('1.00')->add($value))->toThrow(FinancialException::class);
})->with([
    'float' => [0.1], 'null' => [null], 'boolean' => [true], 'scientific' => ['1e3'],
    'localized' => ['1,23'], 'whitespace' => [' 1.23'], 'invalid' => ['no'],
    'empty' => [''], 'array' => [[]], 'nonfinite' => [INF], 'object' => [new \stdClass],
]);

it('rejects zero divisors including decimal and Price zero', function (mixed $zero) {
    expect(fn () => price(1)->divide($zero))->toThrow(DivisionByZeroError::class);
})->with([0, '0.000', '-0', new Price(0)]);

it('validates discount and tax percentage ranges', function () {
    expect(fn () => price(100)->discount('-0.01'))->toThrow(FinancialException::class)
        ->and(fn () => price(100)->discount('100.01'))->toThrow(FinancialException::class)
        ->and(fn () => price(100)->withTax(-1))->toThrow(FinancialException::class)
        ->and(fn () => price(100)->withoutTax(-100))->toThrow(FinancialException::class);
});

it('validates scales and protects the stored amount', function () {
    $price = price('1.00');

    expect(fn () => $price->divide(2, -1))->toThrow(FinancialException::class)
        ->and(fn () => $price->format(-1))->toThrow(FinancialException::class);

    expect(function () use ($price) {
        $price->value = '2.00';
    })->toThrow(\Error::class);
});
