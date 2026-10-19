<?php

namespace Tests\Unit\Mathematical;

use ReeStyleIt\NumberClass\Exceptions\GeometryException;
use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use ReeStyleIt\NumberClass\Mathematical\Dimension;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Circle;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Cone;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Cube;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Cuboid;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Cylinder;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Ellipse;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Pyramid;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Rectangle;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Sphere;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Square;
use ReeStyleIt\NumberClass\Mathematical\Dimension\Triangle;

use function ReeStyleIt\NumberClass\dimension;
use function ReeStyleIt\NumberClass\number;

dataset('dimension factories', [
    ['circle', [], Circle::class],
    ['cone', [4], Cone::class],
    ['cube', [], Cube::class],
    ['cuboid', [4, 5], Cuboid::class],
    ['cylinder', [4], Cylinder::class],
    ['ellipse', [2], Ellipse::class],
    ['equilateralTriangle', [], Triangle::class],
    ['pyramid', [4, 5], Pyramid::class],
    ['rectangle', [4], Rectangle::class],
    ['rightTriangle', [4], Triangle::class],
    ['sphere', [], Sphere::class],
    ['square', [], Square::class],
    ['triangle', [4, 5], Triangle::class],
]);

it('creates fresh shape objects from a stored first measurement', function (string $method, array $arguments, string $class) {
    $factory = dimension(3);
    $shape = $factory->$method(...$arguments);

    expect($factory)->toBeInstanceOf(Dimension::class)
        ->and($shape)->toBeInstanceOf($class)
        ->and($factory->$method(...$arguments))->not->toBe($shape)
        ->and($factory->get())->toBe(3);
})->with('dimension factories');

it('validates the starting measurement for every factory', function (string $method, array $arguments, string $class) {
    expect(fn () => dimension('3')->$method(...$arguments))->toThrow(MustBeNumberException::class)
        ->and(fn () => dimension(-3)->$method(...$arguments))->toThrow(GeometryException::class);
})->with('dimension factories');

it('supports shared access and arithmetic on measurement results', function () {
    $value = number(3);

    expect($value->dimension)->toBeInstanceOf(Dimension::class)
        ->and($value->dimension())->not->toBe($value->dimension)
        ->and($value->dimension->rectangle(4)->area()->multiply(2)->get())->toBe(24)
        ->and($value->dimension->rightTriangle(4)->sideC)->toBe(5.0)
        ->and($value->get())->toBe(3);
});
