<?php

namespace Tests\Unit\Enum;

use ReeStyleIt\NumberClass\Enum\Currency;
use Symfony\Component\Process\Process;

it('provides currency symbols', function (Currency $currency, string $expected) {
    expect($currency->symbol())->toBe($expected);
})->with([
    'euro'            => [Currency::EUR, '€'],
    'US dollar'       => [Currency::USD, '$'],
    'British pound'   => [Currency::GBP, '£'],
    'Japanese yen'    => [Currency::JPY, '¥'],
    'Canadian dollar' => [Currency::CAD, 'CA$'],
    'Indian rupee'    => [Currency::INR, '₹'],
]);

it('resolves symbols for the requested locale', function () {
    expect(Currency::CAD->symbol('en_CA'))->toBe('$')
        ->and(Currency::USD->symbol('en_CA'))->toBe('US$');
});

it('provides a non-empty symbol for every currency case', function () {
    foreach (Currency::cases() as $currency) {
        expect($currency->symbol())->toBeString()->not->toBeEmpty();
    }
});

it('provides fallback symbols when NumberFormatter is unavailable', function () {
    $process = new Process([
        PHP_BINARY,
        '-n',
        dirname(__DIR__, 2) . '/fixtures/currency-symbols-without-intl.php',
        '--simulate-no-intl',
    ]);

    $process->mustRun();

    expect($process->getOutput())->toBe("Currency symbol fallback checks passed.\n");
});
