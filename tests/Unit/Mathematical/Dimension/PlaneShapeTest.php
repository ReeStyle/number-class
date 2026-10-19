<?php

namespace Tests\Unit\Mathematical\Dimension;

use ReeStyleIt\NumberClass\Exceptions\GeometryException;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Circle;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Ellipse;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Rectangle;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Square;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Triangle;
use ReeStyleIt\NumberClass\Number;

it('calculates areas and perimeters of plane shapes', function (string $class, array $dimensions, float $area, float $perimeter) {
    $shape = new $class(...$dimensions);

    expect($shape->area())->toBeInstanceOf(Number::class)
        ->and($shape->area()->get())->toEqualWithDelta($area, 1e-10)
        ->and($shape->surface()->get())->toEqualWithDelta($area, 1e-10)
        ->and($shape->perimeter()->get())->toEqualWithDelta($perimeter, 1e-10);
})->with([
    [Rectangle::class, [3, 4], 12.0, 14.0],
    [Square::class, [3], 9.0, 12.0],
    [Triangle::class, [3, 4, 5], 6.0, 12.0],
    [Triangle::class, [2, 2, 2], sqrt(3), 6.0],
    [Circle::class, [3], 9 * M_PI, 6 * M_PI],
    [Ellipse::class, [3, 2], 6 * M_PI, 15.86543958929059],
]);

it('provides diagonals and circular measurements', function () {
    expect(new Rectangle(3, 4)->diagonal()->get())->toBe(5.0)
        ->and(new Square(3)->diagonal()->get())->toEqualWithDelta(sqrt(18), 1e-12)
        ->and(new Circle(3)->diameter()->get())->toBe(6)
        ->and(new Circle(3)->circumference()->get())->toEqualWithDelta(6 * M_PI, 1e-12)
        ->and(new Ellipse(3, 2)->circumference()->get())->toEqualWithDelta(15.86543958929059, 1e-10);
});

it('preserves measurements and prevents changes to shapes', function () {
    $shape = new Rectangle(3, 4);
    $shape->area()->add(100);

    expect($shape->length)->toBe(3)
        ->and($shape->width)->toBe(4)
        ->and($shape->area()->get())->toBe(12);

    expect(function () use ($shape) {
        $shape->width = 99;
    })->toThrow(\Error::class);
});

it('handles zero-sized and degenerate plane shapes', function () {
    expect(new Rectangle(0, 4)->area()->get())->toBe(0)
        ->and(new Circle(0)->area()->get())->toEqual(0)
        ->and(new Ellipse(0, 3)->perimeter()->get())->toEqual(12)
        ->and(new Triangle(0, 0, 0)->area()->get())->toBe(0.0)
        ->and(new Triangle(1, 2, 3)->area()->get())->toBe(0.0);
});

it('rejects impossible triangles in any side order', function (array $sides) {
    expect(fn () => new Triangle(...$sides))->toThrow(GeometryException::class, 'triangle inequality');
})->with([[[1, 2, 4]], [[4, 1, 2]], [[2, 4, 1]]]);

it('preserves triangle scale and rejects nonfinite results', function () {
    expect(new Triangle(3e150, 4e150, 5e150)->area()->get() / 1e300)->toEqualWithDelta(6, 1e-12)
        ->and(new Triangle(3e-150, 4e-150, 5e-150)->area()->get() / 1e-300)->toEqualWithDelta(6, 1e-12)
        ->and(new Triangle(1e300, 1e300, 1)->area()->get() / 1e300)->toEqualWithDelta(0.5, 1e-12)
        ->and(new Triangle(1e308, 1e308, 1e-308)->area()->get())->toEqualWithDelta(0.5, 1e-12)
        ->and(fn () => new Rectangle(PHP_FLOAT_MAX, 2)->area())->toThrow(GeometryException::class)
        ->and(fn () => new Circle(PHP_FLOAT_MAX)->diameter())->toThrow(GeometryException::class);
});
