<?php

use ReeStyleIt\NumberClass\Enums\Currencies;
use ReeStyleIt\NumberClass\Enums\Locales;

use function ReeStyleIt\NumberClass\currencyFormatter;

it('uses locale enum cases for currency formatting', function (Locales $locale, Currencies $currency, string $expected) {
    $formatter = currencyFormatter(1234.5)->currency($currency);

    expect($formatter->locale($locale))->toBe($formatter)
        ->and($formatter->locale)->toBe($locale->value)
        ->and((string) $formatter)->toBe($expected);
})->with([
    'Dutch' => [Locales::NL_NL, Currencies::EUR, "€\u{00A0}1.234,50"],
    'American English' => [Locales::EN_US, Currencies::USD, '$1,234.50'],
    'British English' => [Locales::EN_GB, Currencies::GBP, '£1,234.50'],
]);

it('accepts locale enum cases for symbol lookup', function () {
    expect(Currencies::CAD->symbol(Locales::EN_CA))->toBe('$')
        ->and(Currencies::USD->symbol(Locales::EN_CA))->toBe('US$')
        ->and(Currencies::CAD->symbol(Locales::EN_CA))->toBe(Currencies::CAD->symbol('en_CA'));
});

it('uses the appropriate regional currency symbols for German-speaking locales', function (Locales $locale, Currencies $currency, string $expected) {
    expect($currency->symbol($locale))->toBe($expected);
})->with([
    'Germany' => [Locales::DE_DE, Currencies::EUR, '€'],
    'Austria' => [Locales::DE_AT, Currencies::EUR, '€'],
    'Switzerland' => [Locales::DE_CH, Currencies::CHF, 'CHF'],
]);

it('converts locale strings to enum cases', function () {
    expect(Locales::from('nl_NL'))->toBe(Locales::NL_NL)
        ->and(Locales::tryFrom('en_US'))->toBe(Locales::EN_US)
        ->and(Locales::tryFrom('not_a_locale'))->toBeNull();
});
