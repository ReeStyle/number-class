<?php

namespace Tests\Unit\Mathematical;

use ReeStyleIt\NumberClass\Exceptions\GeometryException;
use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use ReeStyleIt\NumberClass\Mathematical\Geometry;

use function ReeStyleIt\NumberClass\number;
use function ReeStyleIt\NumberClass\geometry;

it('calculates circle measurements in a new instance', function (
    int|float $value,
    string $method,
    float $expected,
) {
    $circle = new Geometry($value);
    $result = $circle->$method();

    expect($result)->toBeInstanceOf(Geometry::class)
        ->not->toBe($circle)
        ->and($result->get())->toEqualWithDelta($expected, 1e-12)
        ->and($circle->get())->toBe($value);
})->with([
    'surface'                  => [3, 'circleSurface', 28.274333882308138],
    'circumference'            => [3, 'circleCircumference', 18.84955592153876],
    'diameter from radius'     => [3, 'circleDiameter', 6.0],
    'radius from diameter'     => [6, 'circleRadius', 3.0],
    'fractional surface'       => [0.5, 'circleSurface', 0.7853981633974483],
    'fractional circumference' => [0.5, 'circleCircumference', 3.141592653589793],
    'fractional diameter'      => [0.5, 'circleDiameter', 1.0],
    'fractional radius'        => [0.5, 'circleRadius', 0.25],
]);

it('calculates ellipse measurements in a new instance', function (string $method, float $expected) {
    $ellipse = new Geometry(3);
    $result = $ellipse->$method(2);

    expect($result)->toBeInstanceOf(Geometry::class)
        ->not->toBe($ellipse)
        ->and($result->get())->toEqualWithDelta($expected, 1e-8)
        ->and(new Geometry(2)->$method(3)->get())->toEqualWithDelta($expected, 1e-8)
        ->and($ellipse->get())->toBe(3);
})->with([
    'surface'       => ['ellipseSurface', 18.84955592153876],
    'circumference' => ['ellipseCircumference', 15.86543958929059],
]);

it('matches circle measurements for equal ellipse semi-axes', function () {
    $shape = new Geometry(2.5);

    expect($shape->ellipseSurface(2.5)->get())->toEqualWithDelta($shape->circleSurface()->get(), 1e-12)
        ->and($shape->ellipseCircumference(2.5)->get())
        ->toEqualWithDelta($shape->circleCircumference()->get(), 1e-12);
});

it('handles zero-sized circles', function (string $method) {
    expect(new Geometry(0)->$method()->get())->toEqual(0);
})->with(['circleCircumference', 'circleDiameter', 'circleRadius', 'circleSurface']);

it('handles degenerate ellipses', function () {
    expect(new Geometry(0)->ellipseSurface(3)->get())->toEqual(0)
        ->and(new Geometry(3)->ellipseSurface(0)->get())->toEqual(0)
        ->and(new Geometry(0)->ellipseCircumference(3)->get())->toEqual(12)
        ->and(new Geometry(3)->ellipseCircumference(0)->get())->toEqual(12)
        ->and(new Geometry(0)->ellipseCircumference(0)->get())->toEqual(0);
});

it('avoids intermediate overflow and underflow in ellipse circumference', function (float $scale) {
    expect(new Geometry(3 * $scale)->ellipseCircumference(2 * $scale)->get() / $scale)
        ->toEqualWithDelta(15.86543958929059, 1e-8);
})->with([1e200, 1e-200]);

it('supports circle conversions and shape measurements through helpers and Number', function () {
    expect(geometry(3)->circleDiameter()->circleRadius()->circleSurface()->get())
        ->toEqualWithDelta(28.274333882308138, 1e-12)
        ->and(number(3)->geometry->circleCircumference()->get())
        ->toEqualWithDelta(18.84955592153876, 1e-12)
        ->and(number(3)->geometry->ellipseSurface(2)->get())
        ->toEqualWithDelta(18.84955592153876, 1e-12);
});

it('rejects invalid circle lengths', function (string $method, int|float $length) {
    expect(fn () => new Geometry($length)->$method())
        ->toThrow(GeometryException::class, 'Length must be a non-negative finite number.');
})->with(['circleCircumference', 'circleDiameter', 'circleRadius', 'circleSurface'])
    ->with([-1, INF, -INF, NAN]);

it('rejects invalid semi-axis lengths at either ellipse axis', function (string $method, int|float $length) {
    expect(fn () => new Geometry($length)->$method(3))
        ->toThrow(GeometryException::class, 'Length must be a non-negative finite number.')
        ->and(fn () => new Geometry(3)->$method($length))
        ->toThrow(GeometryException::class, 'Length must be a non-negative finite number.');
})->with(['ellipseCircumference', 'ellipseSurface'])->with([-1, INF, -INF, NAN]);

it('rejects nonnumeric stored values for shape measurements', function (string $method) {
    $arguments = str_starts_with($method, 'ellipse') ? [2] : [];

    expect(fn () => new Geometry('3')->$method(...$arguments))->toThrow(MustBeNumberException::class);
})->with([
    'circleCircumference', 'circleDiameter', 'circleRadius', 'circleSurface',
    'ellipseCircumference', 'ellipseSurface',
]);

it('calculates spherical distances in a new instance', function (
    int|float $latitude,
    int|float $longitude,
    int|float $otherLatitude,
    int|float $otherLongitude,
    float $expectedAngle,
) {
    $geometry = new Geometry($longitude, $latitude, 0);
    $distance = $geometry->haversine($otherLongitude, $otherLatitude, 0, radius: 1);

    expect($distance)->toBeInstanceOf(Geometry::class)
        ->not->toBe($geometry)
        ->and($distance->get())->toEqualWithDelta($expectedAngle, 1e-12)
        ->and($geometry->get())->toBe($longitude)
        ->and($geometry->latitude)->toBe($latitude)
        ->and($geometry->elevation)->toBe(0)
        ->and($distance->latitude)->toBeNull();
})->with([
    'same point'          => [52.37, 4.9, 52.37, 4.9, 0.0],
    'quarter equator'     => [0, 0, 0, 90, M_PI / 2],
    'north pole'          => [0, 0, 90, 0, M_PI / 2],
    'opposite points'     => [0, 0, 0, 180, M_PI],
    'both poles'          => [-90, -180, 90, 180, M_PI],
    'across date line'    => [0, 179, 0, -179, deg2rad(2)],
    'longitude boundary'  => [0, -180, 0, 180, 0.0],
    'southern hemisphere' => [-45, 20, 45, 20, M_PI / 2],
]);

it('calculates a known route symmetrically with the default radius', function () {
    $london = new Geometry(-0.1278, 51.5074, 0)->haversine(2.3522, 48.8566, 0)->get();
    $paris = new Geometry(2.3522, 48.8566, 0)->haversine(-0.1278, 51.5074, 0)->get();

    expect($london)->toEqualWithDelta(343556.060341, 1e-3)
        ->and($paris)->toEqualWithDelta($london, 1e-8);
});

it('scales distance according to the supplied radius', function () {
    $kilometres = new Geometry(0, 0)->haversine(90, 0, 0, radius: 6371)->get();
    $metres = new Geometry(0, 0)->haversine(90, 0, 0)->get();

    expect($kilometres)->toEqualWithDelta(6371 * M_PI / 2, 1e-9)
        ->and($metres)->toEqualWithDelta($kilometres * 1000, 1e-8);
});

it('keeps near-identical and near-antipodal distances finite', function () {
    $nearby = new Geometry(4, 52)->haversine(4.00000001, 52.00000001, 0)->get();
    $opposite = new Geometry(10, 45)->haversine(-169.99999999, -45.00000001, 0)->get();

    expect(is_finite($nearby))->toBeTrue()
        ->and($nearby)->toBeGreaterThan(0)->toBeLessThan(0.01)
        ->and(is_finite($opposite))->toBeTrue()
        ->and($opposite)->toEqualWithDelta(6371000 * M_PI, 0.5);
});

it('provides haversine calculations through Number with starting coordinates', function () {
    expect(number(0)->geometry(latitude: 0, elevation: 10)->haversine(90, 0, 10, radius: 1)->get())
        ->toEqualWithDelta(M_PI / 2, 1e-12)
        ->and(number(0)->geometry->latitude)->toBeNull();
});

it('rejects invalid latitudes at either endpoint', function (int|float $latitude) {
    expect(fn () => new Geometry(0, $latitude)->haversine(0, 0, 0))
        ->toThrow(GeometryException::class, GeometryException::invalidLatitude()->getMessage())
        ->and(fn () => new Geometry(0, 0)->haversine(0, $latitude, 0))
        ->toThrow(GeometryException::class, GeometryException::invalidLatitude()->getMessage());
})->with([-90.01, 90.01, INF, -INF, NAN]);

it('rejects invalid longitudes at either endpoint', function (int|float $longitude) {
    expect(fn () => new Geometry($longitude, 0)->haversine(0, 0, 0))
        ->toThrow(GeometryException::class, GeometryException::invalidLongitude()->getMessage())
        ->and(fn () => new Geometry(0, 0)->haversine($longitude, 0, 0))
        ->toThrow(GeometryException::class, GeometryException::invalidLongitude()->getMessage());
})->with([-180.01, 180.01, INF, -INF, NAN]);

it('rejects nonpositive or nonfinite radii', function (int|float $radius) {
    expect(fn () => new Geometry(0, 0)->haversine(90, 0, 0, $radius))
        ->toThrow(GeometryException::class, GeometryException::invalidRadius()->getMessage());
})->with([0, -1, INF, -INF, NAN]);

it('validates stored values before calculating or creating Geometry', function () {
    expect(fn () => new Geometry('0')->haversine(0, 0, 90))->toThrow(MustBeNumberException::class)
        ->and(fn () => number('0')->geometry)->toThrow(MustBeNumberException::class);
});

it('requires an explicit starting latitude for haversine', function () {
    expect(fn () => geometry(0)->haversine(0, 0, 0))
        ->toThrow(GeometryException::class, 'A starting latitude is required for haversine distance.');
});

it('includes positive and negative elevation differences', function (int|float $start, int|float $end) {
    expect(geometry(4, 52, $start)->haversine(4, 52, $end)->get())->toBe(100.0);
})->with([
    'ascending'          => [10, 110],
    'descending'         => [110, 10],
    'below sea level'    => [-110, -10],
    'crossing sea level' => [-50, 50],
]);

it('combines horizontal and vertical distances with matching units', function () {
    // A three-metre equatorial arc and a four-metre climb form a five-metre distance.
    $longitude = rad2deg(3 / 6371000);
    $point = geometry(0, 0, 10);
    $result = $point->haversine($longitude, 0, 14);

    expect($result->get())->toEqualWithDelta(5.0, 1e-12)
        ->and(geometry($longitude, 0, 14)->haversine(0, 0, 10)->get())
        ->toEqualWithDelta(5.0, 1e-12)
        ->and($point->longitude)->toBe(0)
        ->and($point->latitude)->toBe(0)
        ->and($point->elevation)->toBe(10);
});

it('leaves surface distance unchanged at equal elevations', function () {
    expect(geometry(0, 0, 100)->haversine(90, 0, 100, radius: 1)->get())
        ->toEqualWithDelta(M_PI / 2, 1e-12);
});

it('rejects nonfinite elevations at either endpoint', function (float $elevation) {
    expect(fn () => geometry(0, 0, $elevation)->haversine(0, 0, 0))
        ->toThrow(GeometryException::class, 'Elevation must be a finite number.')
        ->and(fn () => geometry(0, 0, 0)->haversine(0, 0, $elevation))
        ->toThrow(GeometryException::class, 'Elevation must be a finite number.');
})->with([INF, -INF, NAN]);

it('keeps starting coordinates readonly', function (string $property) {
    $point = geometry(4, 52, 10);

    expect(function () use ($point, $property) {
        $point->$property = 1;
    })->toThrow(\Error::class);
})->with(['longitude', 'latitude', 'elevation']);
