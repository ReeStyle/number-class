<?php

namespace Tests\Unit\Statistical;

use ReeStyleIt\NumberClass\Statistical\BoxPlot;
use ReeStyleIt\NumberClass\Statistical\BoxPlot\Result;

use function ReeStyleIt\NumberClass\boxPlot;

it('calculates the five number summary with an excluded odd median', function () {
    $values = [9, 1, 7, 3, 5];
    $plot = boxPlot($values);

    expect($plot)->toBeInstanceOf(BoxPlot::class)
        ->and($plot->get())->toBeInstanceOf(Result::class)
        ->and(get_object_vars($plot->get()))->toBe([
            'min' => 1, 'q1' => 2, 'median' => 5, 'q3' => 8, 'max' => 9,
            'iqr' => 6, 'lowerWhisker' => 1, 'upperWhisker' => 9, 'outliers' => [],
        ])
        ->and($values)->toBe([9, 1, 7, 3, 5]);
});

it('supports singleton, even, and constant samples', function (array $values, array $expected) {
    $plot = boxPlot($values);

    expect([$plot->q1, $plot->median, $plot->q3])->toBe($expected)
        ->and($plot->outliers)->toBe([]);
})->with([
    [[1, 2, 3, 4], [1.5, 2.5, 3.5]],
    [[1, 2], [1, 1.5, 2]],
    [[4], [4, 4, 4]],
    [[4, 4, 4, 4], [4, 4, 4]],
]);

it('separates whiskers from sample extremes and retains duplicate outliers', function () {
    $plot = boxPlot([-100, 1, 2, 3, 4, 5, 6, 7, 100]);

    expect($plot->min)->toBe(-100)
        ->and($plot->max)->toBe(100)
        ->and($plot->lowerWhisker)->toBe(1)
        ->and($plot->upperWhisker)->toBe(7)
        ->and($plot->outliers)->toBe([-100, 100])
        ->and(boxPlot([0, 0, 0, 0, 0, 100, 100])->outliers)->toBe([])
        ->and(boxPlot([0, 0, 0, 0, 0, 0, 0, 0, 0, 100, 100])->outliers)->toBe([100, 100]);
});

it('includes values on a fence and protects precomputed results', function () {
    $plot = boxPlot([0, 0, 0, 1, 1, 2, 2, 5]);
    $summary = $plot->get();

    expect($plot->upperWhisker)->toBe(5)
        ->and($plot->outliers)->toBe([])
        ->and($plot->q1)->toBe(0);

    expect($summary)->toBe($plot->get())
        ->and($summary->q1)->toBe(0);

    expect(function () use ($summary) {
        $summary->q1 = 99;
    })->toThrow(\Error::class);

    expect(function () use ($summary) {
        $summary->outliers[] = 99;
    })->toThrow(\Error::class);

    expect(function () use ($plot) {
        $plot->median = 99;
    })->toThrow(\Error::class);
});
