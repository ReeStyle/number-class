<?php

namespace Tests\Unit\Financial;

use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Enum\Currency;
use ReeStyleIt\NumberClass\Enum\Locale;
use ReeStyleIt\NumberClass\Financial\Amortization\Schedule\Line\Wordify;

use function ReeStyleIt\NumberClass\amortization;

it('wordifies every line field and preserves the numeric line', function () {
    $line = amortization(1200)->schedule(0, 12)[0];
    $original = $line->toArray();
    $display = $line->wordify(Currency::USD, Locale::EN_US);

    expect($display)->toBeInstanceOf(Wordify::class)
        ->and($display->toArray())->toBe([
            'period'    => 'first',
            'payment'   => '$100.00',
            'interest'  => '$0.00',
            'principal' => '$100.00',
            'balance'   => '$1,100.00',
        ])
        ->and(json_encode($display, JSON_THROW_ON_ERROR))->toBe(json_encode($display->toArray(), JSON_THROW_ON_ERROR))
        ->and($line->toArray())->toBe($original)
        ->and(json_encode($line, JSON_THROW_ON_ERROR))->toBe(json_encode($original, JSON_THROW_ON_ERROR));
});

it('uses the global locale for the display property and accepts explicit overrides', function () {
    $previous = Base::getDefaultLocale();

    try {
        Base::setDefaultLocale(Locale::NL_NL);
        $line = amortization(1200)->schedule(0, 12)[0];

        expect($line->wordify->period)->toBe('eerste')
            ->and($line->wordify->payment)->toBe("€\u{00A0}100,00")
            ->and($line->wordify->balance)->toBe("€\u{00A0}1.100,00")
            ->and($line->wordify('USD', 'en_US')->period)->toBe('first')
            ->and($line->wordify('USD', 'en_US')->payment)->toBe('$100.00')
            ->and(Base::getDefaultLocale())->toBe('nl_NL');

        Base::setDefaultLocale(null);

        expect($line->wordify->period)->toBe('first')
            ->and($line->wordify->payment)->toBe('€100.00');
    } finally {
        Base::setDefaultLocale($previous);
    }
});

it('uses currency rounding and minor units for display without rounding calculations', function () {
    $line = amortization(100)->schedule(0, 3)[0];

    expect($line->wordify('USD', 'en_US')->payment)->toBe('$33.33')
        ->and($line->wordify('JPY', 'en_US')->payment)->toBe('¥33')
        ->and($line->payment)->toEqualWithDelta(100 / 3, 1e-12);
});

it('protects the display fields and property from reassignment', function () {
    $line = amortization(1200)->schedule(0, 12)[0];
    $display = $line->wordify('USD', 'en_US');

    expect(function () use ($display) {
        $display->payment = 'other';
    })->toThrow(\Error::class);

    expect(function () use ($line, $display) {
        $line->wordify = $display;
    })->toThrow(\Error::class);
});
