<?php

use ReeStyleIt\NumberClass\Enums\Currencies;
use ReeStyleIt\NumberClass\Format\Currency;
use ReeStyleIt\NumberClass\Number;

use function ReeStyleIt\NumberClass\number;
use function ReeStyleIt\NumberClass\currencyFormatter;

it('creates a Number through the number helper without converting its value', function (mixed $value) {
    $number = number(value: $value);

    expect($number)->toBeInstanceOf(Number::class)
        ->and($number->get())->toBe($value);
})->with([
    'integer' => [7],
    'float' => [7.5],
    'numeric string' => ['7'],
]);

it('uses the helpers together in a calculation and currency formatting', function () {
    $number = number(12)->increase(3)->multiply(2);
    $formatter = currencyFormatter($number->get())->currency(Currencies::USD)->locale('en_US');

    expect($number->get())->toBe(30)
        ->and((string) $formatter)->toBe('$30.00');
});

it('creates a currency formatter with the supplied value', function () {
    $formatter = currencyFormatter(value: 1234.5);

    expect($formatter)->toBeInstanceOf(Currency::class)
        ->and($formatter->get())->toBe(1234.5)
        ->and((string) $formatter->currency(Currencies::USD)->locale('en_US'))->toBe('$1,234.50');
});
