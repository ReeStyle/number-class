<?php

namespace Tests\Unit\Enum;

use ReeStyleIt\NumberClass\Enum\Currency;
use ReeStyleIt\NumberClass\Enum\Locale;

use function ReeStyleIt\NumberClass\currencyFormatter;

it('uses locale enum cases for currency formatting', function (Locale $locale, Currency $currency, string $expected) {
    $formatter = currencyFormatter(1234.5)->currency($currency);

    expect($formatter->locale($locale))->toBe($formatter)
        ->and($formatter->locale)->toBe($locale->value)
        ->and((string) $formatter)->toBe($expected);
})->with([
    'Dutch'            => [Locale::NL_NL, Currency::EUR, "€\u{00A0}1.234,50"],
    'American English' => [Locale::EN_US, Currency::USD, '$1,234.50'],
    'British English'  => [Locale::EN_GB, Currency::GBP, '£1,234.50'],
]);

it('accepts locale enum cases for symbol lookup', function () {
    expect(Currency::CAD->symbol(Locale::EN_CA))->toBe('$')
        ->and(Currency::USD->symbol(Locale::EN_CA))->toBe('US$')
        ->and(Currency::CAD->symbol(Locale::EN_CA))->toBe(Currency::CAD->symbol('en_CA'));
});

it('uses the appropriate regional currency symbols for German-speaking locales', function (Locale $locale, Currency $currency, string $expected) {
    expect($currency->symbol($locale))->toBe($expected);
})->with([
    'Germany'     => [Locale::DE_DE, Currency::EUR, '€'],
    'Austria'     => [Locale::DE_AT, Currency::EUR, '€'],
    'Switzerland' => [Locale::DE_CH, Currency::CHF, 'CHF'],
]);

it('converts locale strings to enum cases', function () {
    expect(Locale::from('nl_NL'))->toBe(Locale::NL_NL)
        ->and(Locale::tryFrom('en_US'))->toBe(Locale::EN_US)
        ->and(Locale::tryFrom('not_a_locale'))->toBeNull();
});

it('provides one default currency symbol and its position for a locale', function (Locale $locale, Currency $currency, string $symbol, string $position) {
    expect($locale->currency())->toBe($currency)
        ->and($locale->symbol())->toBe($symbol)
        ->and($locale->symbolPosition())->toBe($position);
})->with([
    'Germany'          => [Locale::DE_DE, Currency::EUR, '€', 'suffix'],
    'Austria'          => [Locale::DE_AT, Currency::EUR, '€', 'prefix'],
    'Switzerland'      => [Locale::DE_CH, Currency::CHF, 'CHF', 'prefix'],
    'Netherlands'      => [Locale::NL_NL, Currency::EUR, '€', 'prefix'],
    'United States'    => [Locale::EN_US, Currency::USD, '$', 'prefix'],
    'Canada in French' => [Locale::FR_CA, Currency::CAD, '$', 'suffix'],
]);

it('contains exactly one currency symbol and position per supported locale', function () {
    $data = require dirname(__DIR__, 3) . '/src/Resources/currency-symbols.php';

    expect(array_keys($data))->toBe(array_map(fn (Locale $locale) => $locale->value, Locale::cases()));

    foreach (Locale::cases() as $locale) {
        expect(array_keys($data[$locale->value]))->toBe(['currency', 'symbol', 'position'])
            ->and($locale->currency())->toBeInstanceOf(Currency::class)
            ->and($locale->symbol())->toBeString()->not->toBeEmpty()
            ->and($locale->symbolPosition())->toBeIn(['prefix', 'suffix']);
    }
});
