<?php

namespace Tests\Unit\Conversion;

use ReeStyleIt\NumberClass\Conversion\Ordinal;
use ReeStyleIt\NumberClass\Enums\Locales;
use ReeStyleIt\NumberClass\Exceptions\EmptyLocaleException;
use ReeStyleIt\NumberClass\Exceptions\MustBeIntegerException;
use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use ReeStyleIt\NumberClass\Exceptions\OrdinalFormattingException;

use function ReeStyleIt\NumberClass\number;
use function ReeStyleIt\NumberClass\ordinal;

it('formats English ordinal endings including the teen exceptions', function (int $value, string $expected) {
    expect(ordinal($value)->format())->toBe($expected)
        ->and((string) ordinal($value))->toBe($expected);
})->with([
    [0, '0th'], [1, '1st'], [2, '2nd'], [3, '3rd'], [4, '4th'],
    [10, '10th'], [11, '11th'], [12, '12th'], [13, '13th'],
    [20, '20th'], [21, '21st'], [22, '22nd'], [23, '23rd'],
    [101, '101st'], [111, '111th'], [112, '112th'], [113, '113th'],
    [121, '121st'], [1001, '1,001st'], [-21, '−21st'],
]);

it('formats ordinals in common languages using locale strings and enums', function (Locales|string $locale, int $value, string $expected) {
    expect(ordinal($value)->locale($locale)->format())->toBe($expected);
})->with([
    ['en', 2, '2nd'], [Locales::EN_GB, 3, '3rd'],
    ['nl', 1, '1ste'], [Locales::NL_NL, 21, '21ste'], [Locales::NL_BE, 8, '8ste'],
    ['fr', 1, '1er'], [Locales::FR_FR, 2, '2e'], [Locales::FR_CA, 21, '21e'],
    ['es', 1, '1.º'], [Locales::ES_ES, 2, '2.º'], [Locales::ES_MX, 21, '21º.'],
    ['it', 1, '1º'], [Locales::IT_IT, 21, '21º'],
    [Locales::DE_DE, 2, '2.'], [Locales::PT_PT, 3, '3º'], [Locales::SV_SE, 2, '2:a'],
]);

it('uses full Dutch ordinal suffixes across hundreds and locale variants', function (int $value, string $expected, Locales|string $locale) {
    expect(ordinal($value)->locale($locale)->format())->toBe($expected);
})->with([
    [0, '0de'], [1, '1ste'], [2, '2de'], [3, '3de'], [4, '4de'],
    [7, '7de'], [8, '8ste'], [9, '9de'], [10, '10de'], [11, '11de'],
    [12, '12de'], [18, '18de'], [19, '19de'], [20, '20ste'],
    [21, '21ste'], [22, '22ste'], [31, '31ste'], [41, '41ste'], [99, '99ste'],
    [100, '100ste'], [101, '101ste'], [102, '102de'], [108, '108ste'],
    [111, '111de'], [119, '119de'], [120, '120ste'], [121, '121ste'],
    [211, '211de'], [1001, '1.001ste'], [-11, '−11de'], [-21, '−21ste'],
])->with(['nl', Locales::NL_NL, Locales::NL_BE, 'nl-NL']);

it('preserves values while configuring the formatter and accessing it from calculations', function () {
    $source = number(20)->add(1);
    $formatter = $source->ordinal;

    expect($formatter)->toBeInstanceOf(Ordinal::class)
        ->and($formatter->locale(Locales::FR_FR))->toBe($formatter)
        ->and($formatter->format())->toBe('21e')
        ->and($formatter->get())->toBe(21)
        ->and($source->get())->toBe(21)
        ->and($source->ordinal()->format())->toBe('21st')
        ->and($formatter->number->add(1)->ordinal->format())->toBe('22nd');
});

it('rejects non-numeric stored values when formatting', function (mixed $value, string $method) {
    expect(fn () => ordinal($value)->$method())->toThrow(MustBeNumberException::class);
})->with([['1'], [true], [null], [[]], [new \stdClass()]])->with(['format', 'wordify']);

it('rejects floating point values rather than rounding them', function (float $value, string $method) {
    expect(fn () => ordinal($value)->$method())->toThrow(MustBeIntegerException::class);
})->with([1.0, 1.5, INF, -INF, NAN])->with(['format', 'wordify']);

it('rejects integers beyond the exact ICU ordinal range', function (int $value, string $method) {
    expect(fn () => ordinal($value)->$method())->toThrow(OrdinalFormattingException::class);
})->with([Ordinal::MAX_SAFE_INTEGER + 1, Ordinal::MIN_SAFE_INTEGER - 1, PHP_INT_MAX, PHP_INT_MIN])->with(['format', 'wordify']);

it('formats integers at the exact ICU range boundaries', function () {
    expect(ordinal(Ordinal::MAX_SAFE_INTEGER)->format())->toBe('9,007,199,254,740,991st')
        ->and(ordinal(Ordinal::MIN_SAFE_INTEGER)->format())->toBe('−9,007,199,254,740,991st');
});

it('rejects empty locales through both public configuration entry points', function (string $locale) {
    $formatter = ordinal(1);

    expect(fn () => $formatter->locale($locale))->toThrow(EmptyLocaleException::class)
        ->and($formatter->locale)->toBe('en_US');

    $formatter->locale = $locale;

    expect(fn () => $formatter->format())->toThrow(EmptyLocaleException::class)
        ->and(fn () => $formatter->wordify())->toThrow(EmptyLocaleException::class);
})->with(['', ' ', "\t\n"]);

it('reports the optional intl dependency when it is unavailable', function (string $method) {
    $command = escapeshellarg(PHP_BINARY) . ' -n '
        . escapeshellarg(dirname(__DIR__, 2) . '/fixtures/ordinal-without-intl.php') . ' ' . escapeshellarg($method);
    exec($command, $output, $exitCode);

    expect($exitCode)->toBe(0)
        ->and(implode("\n", $output))->toBe('Ordinal formatting failed: The intl extension is required for ordinal formatting.');
})->with(['format', 'wordify']);

it('converts ordinal values to words in the configured language', function (Locales|string $locale, int $value, string $expected) {
    $formatter = ordinal($value)->locale($locale);

    expect($formatter->wordify())->toBe($expected)
        ->and($formatter->wordify)->toBe($expected);
})->with([
    ['en', 0, 'zeroth'], ['en', 1, 'first'], ['en', 2, 'second'],
    ['en', 3, 'third'], ['en', 11, 'eleventh'], ['en', 21, 'twenty-first'],
    ['en', -21, 'minus twenty-first'],
    ['nl', 0, 'nulde'], [Locales::NL_NL, 1, 'eerste'], ['nl', 2, 'tweede'],
    ['nl', 8, 'achtste'], ['nl', 11, 'elfde'], [Locales::NL_BE, 21, 'eenentwintigste'],
    ['nl-NL', 31, 'eenendertigste'], ['nl', -11, 'min elfde'],
    [Locales::FR_FR, 1, 'premier'], ['fr', 2, 'deuxième'], ['fr', 21, 'vingt-et-unième'],
    [Locales::ES_ES, 1, 'primero'], ['es', 2, 'segundo'], ['es', 21, 'vigésimo primero'],
    [Locales::IT_IT, 1, 'primo'], ['it', 2, 'secondo'], ['it', 21, 'ventunesimo'],
    ['de', 2, 'zweite'], ['pt', 2, 'segundo'],
]);

it('keeps word conversion on the ordinal class and preserves numeric formatting', function () {
    $source = number(20)->add(1);
    $formatter = $source->ordinal->locale('nl');

    expect($formatter->wordify())->toBe('eenentwintigste')
        ->and($formatter->format())->toBe('21ste')
        ->and((string) $formatter)->toBe('21ste')
        ->and($formatter->get())->toBe(21)
        ->and($formatter->locale)->toBe('nl')
        ->and($source->get())->toBe(21)
        ->and(method_exists($source, 'wordify'))->toBeFalse()
        ->and(function_exists('ReeStyleIt\\NumberClass\\wordify'))->toBeFalse();
});

it('reports locales without a supported ordinal word ruleset', function () {
    expect(fn () => ordinal(1)->locale('ko')->wordify())
        ->toThrow(OrdinalFormattingException::class, 'Ordinal words are not supported for locale ko.');
});
