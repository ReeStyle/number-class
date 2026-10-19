<?php

namespace Tests\Unit\Mathematical\Dimension;

use ReeStyleIt\NumberClass\Mathematical\Dimension\Cone;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Cube;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Cuboid;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Cylinder;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Pyramid;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Sphere;
use ReeStyleIt\NumberClass\Number;

it('calculates closed surface area and volume of solid shapes', function (string $class, array $dimensions, float $surface, float $volume) {
    $shape = new $class(...$dimensions);

    expect($shape->volume())->toBeInstanceOf(Number::class)
        ->and($shape->volume()->get())->toEqualWithDelta($volume, 1e-10)
        ->and($shape->surface()->get())->toEqualWithDelta($surface, 1e-10)
        ->and($shape->surfaceArea()->get())->toEqualWithDelta($surface, 1e-10);
})->with([
    [Cuboid::class, [3, 4, 5], 94.0, 60.0],
    [Cube::class, [3], 54.0, 27.0],
    [Pyramid::class, [6, 6, 4], 96.0, 48.0],
    [Pyramid::class, [6, 8, 4], 48 + (6 * sqrt(32)) + 40, 64.0],
    [Cylinder::class, [3, 4], 42 * M_PI, 36 * M_PI],
    [Cone::class, [3, 4], 24 * M_PI, 12 * M_PI],
    [Sphere::class, [3], 36 * M_PI, 36 * M_PI],
]);

it('calculates solid diagonals and slant heights', function () {
    expect(new Cuboid(2, 3, 6)->diagonal()->get())->toBe(7.0)
        ->and(new Cube(3)->diagonal()->get())->toEqualWithDelta(sqrt(27), 1e-12)
        ->and(new Cone(3, 4)->slantHeight()->get())->toBe(5.0);
});

it('returns zero volume for zero dimensions before overflowing other products', function () {
    expect(new Cuboid(PHP_FLOAT_MAX, 2, 0)->volume()->get())->toBe(0.0)
        ->and(new Pyramid(PHP_FLOAT_MAX, 2, 0)->volume()->get())->toBe(0.0)
        ->and(new Cylinder(PHP_FLOAT_MAX, 0)->volume()->get())->toBe(0.0)
        ->and(new Cone(PHP_FLOAT_MAX, 0)->volume()->get())->toBe(0.0)
        ->and(new Cube(0)->volume()->get())->toBe(0.0)
        ->and(new Sphere(0)->volume()->get())->toEqual(0);
});
