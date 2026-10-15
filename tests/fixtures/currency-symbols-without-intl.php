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

if (class_exists(NumberFormatter::class) && !$simulate) {
    throw new RuntimeException('This check requires NumberFormatter to be unavailable.');
}

$expected = [
    [Currencies::EUR, Locales::NL_NL, '€'],
    [Currencies::EUR, Locales::DE_DE, '€'],
    [Currencies::EUR, Locales::DE_AT, '€'],
    [Currencies::CHF, Locales::DE_CH, 'CHF'],
    [Currencies::USD, Locales::EN_US, '$'],
    [Currencies::CAD, Locales::EN_US, 'CA$'],
    [Currencies::CAD, Locales::EN_CA, '$'],
    [Currencies::USD, Locales::EN_CA, 'US$'],
    [Currencies::GBP, Locales::EN_GB, '£'],
    [Currencies::JPY, Locales::EN_US, '¥'],
    [Currencies::INR, Locales::EN_US, '₹'],
    [Currencies::CAD, 'en-CA', '$'],
    [Currencies::USD, 'en_CA.UTF-8', 'US$'],
    [Currencies::CAD, 'en_CA@currency=CAD', '$'],
    [Currencies::CAD, 'not_a_locale', 'CA$'],
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

try {
    (string) currencyFormatter(7);
    throw new RuntimeException('Full amount formatting must report the missing intl extension.');
} catch (CurrencyFormattingException $exception) {
    if (!str_contains($exception->getMessage(), 'intl extension is required')) {
        throw $exception;
    }
}

echo "Currency symbol fallback checks passed.\n";
