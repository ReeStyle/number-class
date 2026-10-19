<?php

namespace Tests\Unit\Statistical;

use ReeStyleIt\NumberClass\Statistical\Mode;

use function ReeStyleIt\NumberClass\mode;

it('returns every mode in numeric order', function (array $values, array $expected) {
    $result = mode($values);

    expect($result)->toBeInstanceOf(Mode::class)
        ->and($result->get())->toBe($expected);
})->with([
    'single mode' => [[3, 2, 1, 2], [2]],
    'multimodal' => [[3, 2, 3, 1, 2, 4], [2, 3]],
    'no repeats' => [[1, 2, 3], []],
    'singleton' => [[4], []],
    'all equal' => [[4, 4, 4], [4]],
    'decimal values' => [[1.25, 1.5, 1.25, 1.5], [1.25, 1.5]],
    'negative values' => [[-2, -2, -1, 1], [-2]],
    'numerically equal types' => [[1, 1.0, 2], [1]],
    'integer precision' => [[PHP_INT_MAX - 1, PHP_INT_MAX, PHP_INT_MAX], [PHP_INT_MAX]],
]);
