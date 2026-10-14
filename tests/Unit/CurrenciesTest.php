<?php

use ReeStyleIt\IntClass\Enums\Currencies;

it('provides currency symbols', function (Currencies $currency, string $expected) {
    expect($currency->symbol())->toBe($expected);
})->with([
    'euro' => [Currencies::EUR, '€'],
    'US dollar' => [Currencies::USD, '$'],
    'British pound' => [Currencies::GBP, '£'],
    'Japanese yen' => [Currencies::JPY, '¥'],
    'Canadian dollar' => [Currencies::CAD, 'CA$'],
    'Indian rupee' => [Currencies::INR, '₹'],
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
