<?php

use ReeStyleIt\NumberClass\Enums\Currencies;
use ReeStyleIt\NumberClass\Enums\Locales;
use ReeStyleIt\NumberClass\Exceptions\CurrencyFormattingException;

use function ReeStyleIt\NumberClass\currencyFormatter;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$simulate = in_array('--simulate-no-intl', $argv, true);

if ($simulate) {
    require __DIR__ . '/mock-intl-availability.php';
}

if (class_exists(NumberFormatter::class) && ! $simulate) {
    throw new RuntimeException('This check requires NumberFormatter to be unavailable.');
}

$expected = [
    [Currencies::EUR, Locales::NL_NL, '€'],
    [Currencies::EUR, Locales::DE_DE, '€'],
    [Currencies::EUR, Locales::DE_AT, '€'],
    [Currencies::CHF, Locales::DE_CH, 'CHF'],
    [Currencies::USD, Locales::EN_US, '$'],
    [Currencies::CAD, Locales::EN_US, 'CAD'],
    [Currencies::CAD, Locales::EN_CA, '$'],
    [Currencies::USD, Locales::EN_CA, 'USD'],
    [Currencies::GBP, Locales::EN_GB, '£'],
    [Currencies::JPY, Locales::JA_JP, '￥'],
    [Currencies::INR, Locales::EN_IN, '₹'],
    [Currencies::CAD, 'en-CA', '$'],
    [Currencies::CAD, 'en_CA.UTF-8', '$'],
    [Currencies::CAD, 'en_CA@currency=CAD', '$'],
    [Currencies::CAD, 'not_a_locale', 'CAD'],
];

foreach ($expected as [$currency, $locale, $symbol]) {
    if ($currency->symbol($locale) !== $symbol) {
        throw new RuntimeException('Incorrect fallback symbol for ' . $currency->value);
    }
}

foreach (Currencies::cases() as $currency) {
    foreach (Locales::cases() as $locale) {
        if ($currency->symbol($locale) === '') {
            throw new RuntimeException('Empty fallback symbol for ' . $currency->value);
        }
    }
}

foreach (Locales::cases() as $locale) {
    if ($locale->currency()->symbol($locale) !== $locale->symbol()) {
        throw new RuntimeException('Default symbol does not match the locale: ' . $locale->value);
    }

    if (! in_array($locale->symbolPosition(), ['prefix', 'suffix'], true)) {
        throw new RuntimeException('Invalid symbol position for ' . $locale->value);
    }
}

try {
    (string) currencyFormatter(7);
    throw new RuntimeException('Full amount formatting must report the missing intl extension.');
} catch (CurrencyFormattingException $exception) {
    if (! str_contains($exception->getMessage(), 'intl extension is required')) {
        throw $exception;
    }
}

\ReeStyleIt\NumberClass\Base::setDefaultLocale(Locales::EN_CA);

if (Currencies::CAD->symbol() !== '$' || Currencies::CAD->symbol(Locales::EN_US) !== 'CAD') {
    throw new RuntimeException('Global locale and explicit overrides must apply without intl.');
}

\ReeStyleIt\NumberClass\Base::setDefaultLocale(null);

echo "Currency symbol fallback checks passed.\n";
