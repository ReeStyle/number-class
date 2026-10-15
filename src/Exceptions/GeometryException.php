<?php

namespace ReeStyleIt\NumberClass\Exceptions;

class GeometryException extends BaseInvalidArgumentException
{
    protected const string DEFAULT_MESSAGE = 'Invalid geometry dimensions.';

    public static function invalidElevation(): self
    {
        return new self('Elevation must be a finite number.');
    }

    public static function invalidLatitude(): self
    {
        return new self('Latitude must be a finite number between -90 and 90 degrees.');
    }

    public static function invalidLength(): self
    {
        return new self('Length must be a non-negative finite number.');
    }

    public static function invalidLongitude(): self
    {
        return new self('Longitude must be a finite number between -180 and 180 degrees.');
    }

    public static function invalidRadius(): self
    {
        return new self('Radius must be a positive finite number.');
    }

    public static function missingLatitude(): self
    {
        return new self('A starting latitude is required for haversine distance.');
    }
}
