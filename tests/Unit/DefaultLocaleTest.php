<?php

namespace Tests\Unit;

use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Conversion\Ordinal;
use ReeStyleIt\NumberClass\Enums\Currencies;
use ReeStyleIt\NumberClass\Enums\Locales;
use ReeStyleIt\NumberClass\Exceptions\EmptyLocaleException;
use ReeStyleIt\NumberClass\Format\Currency;
use ReeStyleIt\NumberClass\Number;

use function ReeStyleIt\NumberClass\currencyFormatter;
use function ReeStyleIt\NumberClass\number;
use function ReeStyleIt\NumberClass\ordinal;
use function ReeStyleIt\NumberClass\setDefaultLocale;

beforeEach(function () {
    $this->previousDefaultLocale = Base::getDefaultLocale();
    Base::setDefaultLocale(null);
});

afterEach(function () {
    Base::setDefaultLocale($this->previousDefaultLocale);
});

it('shares the default locale across value classes for strings and enums', function (Locales|string $locale) {
    setDefaultLocale($locale);

    expect(Base::getDefaultLocale())->toBe('nl_NL')
        ->and(Ordinal::getDefaultLocale())->toBe('nl_NL')
        ->and(Currency::getDefaultLocale())->toBe('nl_NL')
        ->and(new Ordinal(21)->format())->toBe('21ste')
        ->and(ordinal(21)->toWords)->toBe('eenentwintigste')
        ->and(number(21)->ordinal->format())->toBe('21ste')
        ->and(number(21)->ordinal()->toWords())->toBe('eenentwintigste');
})->with(['nl_NL', Locales::NL_NL]);

it('applies the default locale to currency formatting and default symbol lookup', function () {
    Base::setDefaultLocale(Locales::EN_US);

    expect((string) new Currency(1234.5)->currency('USD'))->toBe('$1,234.50')
        ->and((string) currencyFormatter(1234.5)->currency('USD'))->toBe('$1,234.50')
        ->and((string) number(1234.5)->currency->currency('USD'))->toBe('$1,234.50');

    Base::setDefaultLocale('nl_NL');

    expect(Currencies::EUR->symbol())->toBe('€')
        ->and(Currencies::EUR->symbol('en_US'))->toBe('€')
        ->and(Currencies::USD->symbol())->toBe(Currencies::USD->symbol('nl_NL'));
});

it('preserves existing instances and allows local overrides', function () {
    $existing = ordinal(1);
    Base::setDefaultLocale('nl_NL');
    $dutch = ordinal(1);
    $currency = currencyFormatter(7)->locale('en_US')->currency('USD');

    Base::setDefaultLocale('fr_FR');

    expect($existing->format())->toBe('1st')
        ->and($dutch->format())->toBe('1ste')
        ->and($dutch->locale('it')->toWords)->toBe('primo')
        ->and((string) $currency)->toBe('$7.00')
        ->and($dutch->ordinal->format())->toBe('1er')
        ->and(ordinal(1)->locale('en_US')->format())->toBe('1st');
});

it('restores original class defaults when the global override is cleared', function () {
    Base::setDefaultLocale('fr_FR');
    setDefaultLocale(null);

    expect(Base::getDefaultLocale())->toBeNull()
        ->and(ordinal(1)->locale)->toBe('en_US')
        ->and(currencyFormatter(1)->locale)->toBe('nl_NL')
        ->and(Currencies::USD->symbol())->toBe('$');
});

it('rejects blank global locales without changing the current default', function (string $locale) {
    Base::setDefaultLocale('nl_NL');

    expect(fn () => setDefaultLocale($locale))->toThrow(EmptyLocaleException::class)
        ->and(Base::getDefaultLocale())->toBe('nl_NL');
})->with(['', ' ', "\t\n"]);
