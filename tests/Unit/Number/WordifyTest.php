<?php

namespace Tests\Unit\Number;

use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Enum\Locale;
use ReeStyleIt\NumberClass\Exceptions\EmptyLocaleException;
use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use ReeStyleIt\NumberClass\Exceptions\NumberFormattingException;

use function ReeStyleIt\NumberClass\number;

it('spells cardinal numbers in common languages', function (int|float $value, Locale|string $locale, string $expected) {
    $number = number($value);

    expect($number->wordify($locale))->toBe($expected)
        ->and($number->get())->toBe($value);
})->with([
    [0, 'en', 'zero'], [1, 'en', 'one'], [21, Locale::EN_US, 'twenty-one'],
    [-21, 'en', 'minus twenty-one'], [1.25, 'en', 'one point two five'],
    [0, 'nl', 'nul'], [21, Locale::NL_NL, 'eenentwintig'],
    [21, Locale::FR_FR, 'vingt-et-un'], [21, Locale::ES_ES, 'veintiuno'],
    [21, Locale::IT_IT, 'ventuno'],
]);

it('uses the global locale for method and property access while allowing explicit overrides', function () {
    $previous = Base::getDefaultLocale();

    try {
        Base::setDefaultLocale('nl_NL');
        $number = number(21);

        expect($number->wordify)->toBe('eenentwintig')
            ->and($number->wordify())->toBe('eenentwintig')
            ->and($number->wordify('en'))->toBe('twenty-one')
            ->and($number->ordinal->wordify)->toBe('eenentwintigste')
            ->and(number(20)->add(1)->number->wordify)->toBe('eenentwintig');

        Base::setDefaultLocale(null);

        expect(number(21)->wordify)->toBe('twenty-one');
    } finally {
        Base::setDefaultLocale($previous);
    }
});

it('rejects non-number input before spelling words', function (mixed $value) {
    expect(fn () => number($value)->wordify)->toThrow(MustBeNumberException::class);
})->with([['21'], [null], [true], [[]]]);

it('rejects nonfinite and out-of-range values', function (int|float $value) {
    expect(fn () => number($value)->wordify)->toThrow(NumberFormattingException::class);
})->with([INF, -INF, NAN, Base::MAX_SAFE_INTEGER + 1, Base::MIN_SAFE_INTEGER - 1]);

it('rejects blank locale overrides', function (string $locale) {
    expect(fn () => number(21)->wordify($locale))->toThrow(EmptyLocaleException::class);
})->with(['', ' ', "\t\n"]);

it('keeps the wordify property read-only', function () {
    $number = number(21);

    expect(function () use ($number) {
        $number->wordify = 'other';
    })->toThrow(\Error::class);
});

it('reports missing intl support for cardinal word conversion', function () {
    $command = escapeshellarg(PHP_BINARY) . ' -n '
        . escapeshellarg(dirname(__DIR__, 2) . '/fixtures/number-wordify-without-intl.php');
    exec($command, $output, $exitCode);

    expect($exitCode)->toBe(0)
        ->and(implode("\n", $output))->toBe('Number formatting failed: The intl extension is required for number word conversion.');
});
