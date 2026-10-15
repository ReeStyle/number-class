<?php

namespace ReeStyleIt\NumberClass\Mathematical;

use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Exceptions\GeometryException;

class Geometry extends Base
{
    public readonly int|float $elevation;
    public readonly int|float|null $latitude;

    public mixed $longitude {
        get => $this->value;
    }

    /**
     * For geographic points, value is longitude and latitude must be supplied.
     * Shape calculations continue to use value as a length.
     */
    public function __construct(mixed $value, int|float|null $latitude = null, int|float $elevation = 0)
    {
        parent::__construct($value);

        $this->latitude = $latitude;
        $this->elevation = $elevation;
    }

    /**
     * Treat the stored value as a circle's radius.
     */
    public function circleCircumference(): self
    {
        $this->assertNumber();
        $this->assertLength($this->value);

        return new self(2 * M_PI * $this->value);
    }

    /**
     * Convert the stored radius to a diameter.
     */
    public function circleDiameter(): self
    {
        $this->assertNumber();
        $this->assertLength($this->value);

        return new self(2 * $this->value);
    }

    /**
     * Convert the stored diameter to a radius.
     */
    public function circleRadius(): self
    {
        $this->assertNumber();
        $this->assertLength($this->value);

        return new self($this->value / 2);
    }

    /**
     * Treat the stored value as a circle's radius and return its area.
     */
    public function circleSurface(): self
    {
        $this->assertNumber();
        $this->assertLength($this->value);

        return new self(M_PI * ($this->value * $this->value));
    }

    /**
     * Treat both values as semi-axis lengths and use Ramanujan's second approximation.
     */
    public function ellipseCircumference(int|float $otherSemiAxis): self
    {
        $this->assertNumber();
        $this->assertLength($this->value);
        $this->assertLength($otherSemiAxis);

        $major = max($this->value, $otherSemiAxis);
        $minor = min($this->value, $otherSemiAxis);

        if ($minor == 0) {
            return new self(4 * $major);
        }

        // Normalize before calculating h to avoid squaring or adding large lengths.
        $ratio = $minor / $major;
        $h = ((1 - $ratio) / (1 + $ratio)) ** 2;
        $factor = M_PI * (1 + $ratio) * (1 + 3 * $h / (10 + sqrt(4 - 3 * $h)));

        return new self($major * $factor);
    }

    /**
     * Treat both values as semi-axis lengths and return the ellipse's area.
     */
    public function ellipseSurface(int|float $otherSemiAxis): self
    {
        $this->assertNumber();
        $this->assertLength($this->value);
        $this->assertLength($otherSemiAxis);

        return new self(($this->value * $otherSemiAxis) * M_PI);
    }

    /**
     * Combine haversine surface distance with the elevation difference.
     * Coordinates are in degrees; radius, elevations, and distance use the same units (metres by default).
     */
    public function haversine(
        int|float $longitude,
        int|float $latitude,
        int|float $elevation,
        int|float $radius = 6371000.0,
    ): self {
        $this->assertNumber();

        if ($this->latitude === null) {
            throw GeometryException::missingLatitude();
        }

        $this->assertLongitude($this->longitude);
        $this->assertLongitude($longitude);
        $this->assertLatitude($this->latitude);
        $this->assertLatitude($latitude);
        $this->assertElevation($this->elevation);
        $this->assertElevation($elevation);

        if (!is_finite($radius) || $radius <= 0) {
            throw GeometryException::invalidRadius();
        }

        $startingLatitude = deg2rad($this->latitude);
        $destinationLatitude = deg2rad($latitude);
        $latitudeDifference = deg2rad($latitude - $this->latitude);
        $longitudeDifference = deg2rad($longitude - $this->longitude);

        $haversine = sin($latitudeDifference / 2) ** 2
            + cos($startingLatitude) * cos($destinationLatitude) * sin($longitudeDifference / 2) ** 2;

        // Floating point error can otherwise put the square roots outside their domain.
        $haversine = max(0.0, min(1.0, $haversine));
        $angle = 2 * atan2(sqrt($haversine), sqrt(1 - $haversine));

        return new self(hypot($radius * $angle, $elevation - $this->elevation));
    }

    private function assertElevation(int|float $elevation): void
    {
        if (!is_finite($elevation)) {
            throw GeometryException::invalidElevation();
        }
    }

    private function assertLatitude(int|float $latitude): void
    {
        if (!is_finite($latitude) || $latitude < -90 || $latitude > 90) {
            throw GeometryException::invalidLatitude();
        }
    }

    private function assertLength(int|float $length): void
    {
        if (!is_finite($length) || $length < 0) {
            throw GeometryException::invalidLength();
        }
    }

    private function assertLongitude(int|float $longitude): void
    {
        if (!is_finite($longitude) || $longitude < -180 || $longitude > 180) {
            throw GeometryException::invalidLongitude();
        }
    }
}
