<?php

namespace Tests\Unit\Statistical;

use ReeStyleIt\NumberClass\Statistical\Median;

use function ReeStyleIt\NumberClass\median;

it('calculates medians from sorted and unsorted samples', function (array $values, int|float $expected) {
    $original = $values;
    $result = median($values);

    expect($result)->toBeInstanceOf(Median::class)
        ->and($result->get())->toBe($expected)
        ->and($values)->toBe($original);
})->with([
    'odd' => [[7, 1, 3], 3],
    'even' => [[4, 1, 2, 3], 2.5],
    'singleton' => [[3.5], 3.5],
    'negative' => [[-1, -5, -3], -3],
    'mixed types' => [[1, 2.5], 1.75],
    'duplicate' => [[2, 2, 2, 2], 2],
    'maximum integer' => [[PHP_INT_MAX, PHP_INT_MAX], PHP_INT_MAX],
    'large finite floats' => [[PHP_FLOAT_MAX, PHP_FLOAT_MAX / 2], PHP_FLOAT_MAX * 0.75],
    'opposite finite extremes' => [[-PHP_FLOAT_MAX, PHP_FLOAT_MAX], 0.0],
]);

it('accepts iterable input and snapshots its values', function () {
    $values = new \ArrayIterator(['a' => 3, 'b' => 1, 'c' => 2]);
    $median = new Median($values);
    $values['a'] = 99;

    expect($median->values)->toBe([1, 2, 3])
        ->and($median->get())->toBe(2);
});
