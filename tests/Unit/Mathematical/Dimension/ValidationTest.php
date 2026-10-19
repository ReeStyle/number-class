<?php

namespace Tests\Unit\Mathematical\Dimension;

use ReeStyleIt\NumberClass\Exceptions\GeometryException;
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

it('validates every dimension of every shape', function (string $class, array $dimensions) {
    foreach (array_keys($dimensions) as $index) {
        foreach ([-1, INF, -INF, NAN] as $invalid) {
            $arguments = $dimensions;
            $arguments[$index] = $invalid;
            expect(fn () => new $class(...$arguments))->toThrow(GeometryException::class);
        }
    }
})->with([
    [Rectangle::class, [3, 4]], [Square::class, [3]], [Triangle::class, [3, 4, 5]],
    [Circle::class, [3]], [Ellipse::class, [3, 2]], [Cuboid::class, [3, 4, 5]],
    [Cube::class, [3]], [Pyramid::class, [3, 4, 5]], [Cylinder::class, [3, 4]],
    [Cone::class, [3, 4]], [Sphere::class, [3]],
]);
