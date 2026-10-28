<?php

namespace Tests\Unit\Statistical;

use ReeStyleIt\NumberClass\Exceptions\StatisticalException;
use ReeStyleIt\NumberClass\Statistical\BoxPlot;
use ReeStyleIt\NumberClass\Statistical\Median;
use ReeStyleIt\NumberClass\Statistical\Mode;

it('rejects empty or nonfinite numeric samples consistently', function (string $class, array $values) {
    expect(fn () => new $class($values))->toThrow(StatisticalException::class);
})->with([Median::class, Mode::class, BoxPlot::class])->with([
    'empty'             => [[]],
    'numeric string'    => [[1, '2']],
    'boolean'           => [[true]],
    'null'              => [[null]],
    'nested array'      => [[[1]]],
    'object'            => [[new \stdClass]],
    'infinite'          => [[INF]],
    'negative infinite' => [[-INF]],
    'NaN'               => [[NAN]],
]);

it('reports box plot range overflow', function () {
    expect(fn () => new BoxPlot([-PHP_FLOAT_MAX, PHP_FLOAT_MAX]))->toThrow(StatisticalException::class);
});
