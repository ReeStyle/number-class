<?php

namespace Tests\Unit\Enums;

use ReeStyleIt\NumberClass\Enums\Currencies;
use Symfony\Component\Process\Process;

it('provides currency symbols', function (Currencies $currency, string $expected) {
    expect($currency->symbol())->toBe($expected);
})->with([
    'euro'            => [Currencies::EUR, '€'],
    'US dollar'       => [Currencies::USD, '$'],
    'British pound'   => [Currencies::GBP, '£'],
    'Japanese yen'    => [Currencies::JPY, '¥'],
    'Canadian dollar' => [Currencies::CAD, 'CA$'],
    'Indian rupee'    => [Currencies::INR, '₹'],
]);

it('resolves symbols for the requested locale', function () {
    expect(Currencies::CAD->symbol('en_CA'))->toBe('$')
        ->and(Currencies::USD->symbol('en_CA'))->toBe('US$');
});

it('provides a non-empty symbol for every currency case', function () {
    foreach (Currencies::cases() as $currency) {
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
