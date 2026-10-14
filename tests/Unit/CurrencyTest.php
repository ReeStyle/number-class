<?php

use ReeStyleIt\IntClass\Base;
use ReeStyleIt\IntClass\Enums\Currencies;
use ReeStyleIt\IntClass\Format\Currency;
use ReeStyleIt\IntClass\Number;

it('formats currency for the configured locale', function (int|float $value, string $currency, string $locale, string $expected) {
    $format = (new Currency($value))->currency($currency)->locale($locale);

    expect((string) $format)->toBe($expected);
})->with([
    'Dutch euros' => [1234.5, 'EUR', 'nl_NL', "€\u{00A0}1.234,50"],
    'US dollars' => [1234.5, 'USD', 'en_US', '$1,234.50'],
    'zero' => [0, 'USD', 'en_US', '$0.00'],
    'negative amount' => [-1234.5, 'USD', 'en_US', '-$1,234.50'],
    'rounding' => [1.236, 'USD', 'en_US', '$1.24'],
    'yen without decimals' => [1234, 'JPY', 'en_US', '¥1,234'],
]);

it('normalizes lowercase currency codes', function () {
    $format = (new Currency(7))->currency('usd')->locale('en_US');

    expect($format->currency)->toBe('USD')
        ->and($format)->toBeInstanceOf(Base::class)
        ->and($format->get())->toBe(7)
        ->and((string) $format)->toBe('$7.00');
});

it('returns the inherited value through its getter', function () {
    $format = new Currency(7);

    expect($format->get())->toBe(7);
});

it('accepts a currency enum when formatting a Number', function () {
    $format = new Number(1234.5)->currency()->currency(Currencies::USD)->locale('en_US');

    expect($format->currency)->toBe('USD')
        ->and((string) $format)->toBe('$1,234.50');
});

it('rejects malformed currency codes', function (string $currency) {
    expect(fn () => (new Currency(7))->currency($currency))->toThrow(InvalidArgumentException::class);
})->with(['', 'EU', 'EURO', '123', '€']);

it('rejects an empty locale', function () {
    expect(fn () => (new Currency(7))->locale(''))->toThrow(InvalidArgumentException::class);
});

it('rejects non-finite currency values', function (float $value) {
    expect(fn () => (string) new Currency($value))
        ->toThrow(UnexpectedValueException::class);
})->with([INF, -INF, NAN]);

it('formats a Number without changing its value', function () {
    $number = new Number(1234.5);

    expect((string) $number->currency()->currency('USD')->locale('en_US'))->toBe('$1,234.50')
        ->and($number->get())->toBe(1234.5);
});

it('uses the default currency format', function () {
    $format = new Number(1234.5)->currency();

    expect($format)->toBeInstanceOf(Currency::class)
        ->and($format->get())->toBe(1234.5)
        ->and($format->currency)->toBe('EUR')
        ->and($format->locale)->toBe('nl_NL')
        ->and((string) $format)->toBe("€\u{00A0}1.234,50");
});

it('validates the Number before formatting currency', function (mixed $value) {
    expect(fn () => new Number($value)->currency())
        ->toThrow(UnexpectedValueException::class, 'Value must be an integer or float.');
})->with([
    'numeric string' => ['7'],
    'null' => [null],
    'boolean' => [true],
    'array' => [[]],
    'object' => [new stdClass()],
]);

it('returns the same formatter from configuration methods and keeps its amount', function () {
    $format = new Currency(7);

    expect($format->currency(Currencies::USD))->toBe($format)
        ->and($format->locale('en_US'))->toBe($format)
        ->and((string) $format)->toBe('$7.00')
        ->and($format->get())->toBe(7);

    expect((string) $format->currency(Currencies::EUR)->locale('nl_NL'))->toBe("€\u{00A0}7,00")
        ->and($format->get())->toBe(7);
});

it('rejects invalid stored values when formatting directly', function (mixed $value) {
    expect(fn () => (string) new Currency($value))
        ->toThrow(UnexpectedValueException::class, 'Value must be an integer or float.');
})->with([
    'numeric string' => ['7'],
    'null' => [null],
    'boolean' => [true],
    'array' => [[]],
    'object' => [new stdClass()],
]);
