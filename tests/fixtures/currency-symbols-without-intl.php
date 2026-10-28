<?php

use ReeStyleIt\NumberClass\Enum\Currency;
use ReeStyleIt\NumberClass\Enum\Locale;
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
    [Currency::EUR, Locale::NL_NL, '€'],
    [Currency::EUR, Locale::DE_DE, '€'],
    [Currency::EUR, Locale::DE_AT, '€'],
    [Currency::CHF, Locale::DE_CH, 'CHF'],
    [Currency::USD, Locale::EN_US, '$'],
    [Currency::CAD, Locale::EN_US, 'CAD'],
    [Currency::CAD, Locale::EN_CA, '$'],
    [Currency::USD, Locale::EN_CA, 'USD'],
    [Currency::GBP, Locale::EN_GB, '£'],
    [Currency::JPY, Locale::JA_JP, '￥'],
    [Currency::INR, Locale::EN_IN, '₹'],
    [Currency::CAD, 'en-CA', '$'],
    [Currency::CAD, 'en_CA.UTF-8', '$'],
    [Currency::CAD, 'en_CA@currency=CAD', '$'],
    [Currency::CAD, 'not_a_locale', 'CAD'],
];

foreach ($expected as [$currency, $locale, $symbol]) {
    if ($currency->symbol($locale) !== $symbol) {
        throw new RuntimeException('Incorrect fallback symbol for ' . $currency->value);
    }
}

foreach (Currency::cases() as $currency) {
    foreach (Locale::cases() as $locale) {
        if ($currency->symbol($locale) === '') {
            throw new RuntimeException('Empty fallback symbol for ' . $currency->value);
        }
    }
}

foreach (Locale::cases() as $locale) {
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

\ReeStyleIt\NumberClass\Base::setDefaultLocale(Locale::EN_CA);

if (Currency::CAD->symbol() !== '$' || Currency::CAD->symbol(Locale::EN_US) !== 'CAD') {
    throw new RuntimeException('Global locale and explicit overrides must apply without intl.');
}

\ReeStyleIt\NumberClass\Base::setDefaultLocale(null);

echo "Currency symbol fallback checks passed.\n";
