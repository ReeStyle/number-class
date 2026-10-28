<?php

namespace Tests\Unit\Format;

use stdClass;
use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Enum\Currency as CurrencyEnum;
use ReeStyleIt\NumberClass\Exceptions\CurrencyFormattingException;
use ReeStyleIt\NumberClass\Exceptions\EmptyLocaleException;
use ReeStyleIt\NumberClass\Exceptions\InvalidCurrencyCodeException;
use ReeStyleIt\NumberClass\Exceptions\MustBeFiniteCurrencyValue;
use ReeStyleIt\NumberClass\Format\Currency;
use ReeStyleIt\NumberClass\Number;

it('formats currency for the configured locale', function (int|float $value, string $currency, string $locale, string $expected) {
    $format = (new Currency($value))->currency($currency)->locale($locale);

    expect((string) $format)->toBe($expected);
})->with([
    'Dutch euros'          => [1234.5, 'EUR', 'nl_NL', "€\u{00A0}1.234,50"],
    'US dollars'           => [1234.5, 'USD', 'en_US', '$1,234.50'],
    'zero'                 => [0, 'USD', 'en_US', '$0.00'],
    'negative amount'      => [-1234.5, 'USD', 'en_US', '-$1,234.50'],
    'rounding'             => [1.236, 'USD', 'en_US', '$1.24'],
    'yen without decimals' => [1234, 'JPY', 'en_US', '¥1,234'],
]);

it('normalizes lowercase currency codes', function () {
    $format = (new Currency(7))->currency('usd')->locale('en_US');

    expect($format->currencyCode)->toBe('USD')
        ->and($format)->toBeInstanceOf(Base::class)
        ->and($format->get())->toBe(7)
        ->and((string) $format)->toBe('$7.00');
});

it('returns the inherited value through its getter', function () {
    $format = new Currency(7);

    expect($format->get())->toBe(7);
});

it('accepts a currency enum when formatting a Number', function () {
    $format = new Number(1234.5)->currency->currency(CurrencyEnum::USD)->locale('en_US');

    expect($format->currencyCode)->toBe('USD')
        ->and((string) $format)->toBe('$1,234.50');
});

it('rejects malformed currency codes', function (string $currency) {
    expect(fn () => (new Currency(7))->currency($currency))
        ->toThrow(InvalidCurrencyCodeException::class, 'Currency must be a three-letter currency code.');
})->with(['', 'EU', 'EURO', '123', '€']);

it('rejects an empty locale', function (string $locale) {
    expect(fn () => (new Currency(7))->locale($locale))
        ->toThrow(EmptyLocaleException::class, 'Locale must not be empty.');
})->with(['', ' ', "\t\n"]);

it('rejects non-finite currency values', function (float $value) {
    expect(fn () => (string) new Currency($value))
        ->toThrow(MustBeFiniteCurrencyValue::class, 'Currency value must be finite.');
})->with([INF, -INF, NAN]);

it('throws a named exception when the formatter cannot produce a string', function () {
    $format = new Currency(7);
    $format->currencyCode = "\xFF";

    expect(fn () => (string) $format)
        ->toThrow(CurrencyFormattingException::class, 'Currency formatting failed:');
});

it('formats a Number without changing its value', function () {
    $number = new Number(1234.5);

    expect((string) $number->currency->currency('USD')->locale('en_US'))->toBe('$1,234.50')
        ->and($number->get())->toBe(1234.5);
});

it('uses the default currency format', function () {
    $format = new Number(1234.5)->currency;

    expect($format)->toBeInstanceOf(Currency::class)
        ->and($format->get())->toBe(1234.5)
        ->and($format->currencyCode)->toBe('EUR')
        ->and($format->locale)->toBe('nl_NL')
        ->and((string) $format)->toBe("€\u{00A0}1.234,50");
});

it('validates the Number before formatting currency', function (mixed $value) {
    expect(fn () => new Number($value)->currency)
        ->toThrow(MustBeNumberException::class, 'Value must be an integer or float.');
})->with([
    'numeric string' => ['7'],
    'null'           => [null],
    'boolean'        => [true],
    'array'          => [[]],
    'object'         => [new stdClass()],
]);

it('returns the same formatter from configuration methods and keeps its amount', function () {
    $format = new Currency(7);

    expect($format->currency(CurrencyEnum::USD))->toBe($format)
        ->and($format->locale('en_US'))->toBe($format)
        ->and((string) $format)->toBe('$7.00')
        ->and($format->get())->toBe(7);

    expect((string) $format->currency(CurrencyEnum::EUR)->locale('nl_NL'))->toBe("€\u{00A0}7,00")
        ->and($format->get())->toBe(7);
});

it('creates fresh currency helpers without copying formatter configuration', function () {
    $format = new Currency(7)->currency('USD')->locale('en_US');
    $method = $format->currency();
    $property = $format->currency;

    expect($method)->toBeInstanceOf(Currency::class)->not->toBe($format)
        ->and($property)->toBeInstanceOf(Currency::class)->not->toBe($format)->not->toBe($method)
        ->and($method->get())->toBe(7)
        ->and($property->get())->toBe(7)
        ->and($method->currencyCode)->toBe('EUR')
        ->and($method->locale)->toBe('nl_NL')
        ->and($format->currencyCode)->toBe('USD')
        ->and($format->locale)->toBe('en_US');
});

it('rejects invalid stored values when formatting directly', function (mixed $value) {
    expect(fn () => (string) new Currency($value))
        ->toThrow(MustBeNumberException::class, 'Value must be an integer or float.');
})->with([
    'numeric string' => ['7'],
    'null'           => [null],
    'boolean'        => [true],
    'array'          => [[]],
    'object'         => [new stdClass()],
]);
