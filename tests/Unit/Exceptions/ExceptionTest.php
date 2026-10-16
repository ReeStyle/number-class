<?php

namespace Tests\Unit\Exceptions;

use InvalidArgumentException;
use RoundingMode;
use RuntimeException;
use ReeStyleIt\NumberClass\Exceptions\BaseInvalidArgumentException;
use ReeStyleIt\NumberClass\Exceptions\BaseRuntimeException;
use ReeStyleIt\NumberClass\Exceptions\CurrencyFormattingException;
use ReeStyleIt\NumberClass\Exceptions\CurrencySymbolLookupException;
use ReeStyleIt\NumberClass\Exceptions\EmptyLocaleException;
use ReeStyleIt\NumberClass\Exceptions\GeometryException;
use ReeStyleIt\NumberClass\Exceptions\InvalidCurrencyCodeException;
use ReeStyleIt\NumberClass\Exceptions\MustBeFiniteCurrencyValue;
use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;
use ReeStyleIt\NumberClass\Exceptions\MustBeIntegerException;
use ReeStyleIt\NumberClass\Exceptions\MustBePositiveNumberException;
use ReeStyleIt\NumberClass\Exceptions\PythagorasException;
use ReeStyleIt\NumberClass\Exceptions\RoundingException;
use ReeStyleIt\NumberClass\Exceptions\TimeException;

it('provides default messages and allows catching named exceptions through their shared base', function (string $exception, string $message) {
    $error = new $exception();

    expect($error->getMessage())->toBe($message)
        ->and(fn () => throw $error)->toThrow(BaseInvalidArgumentException::class, $message)
        ->and(fn () => throw $error)->toThrow(InvalidArgumentException::class, $message);
})->with([
    [BaseInvalidArgumentException::class, 'Invalid argument.'],
    [CurrencyFormattingException::class, 'Currency formatting failed.'],
    [EmptyLocaleException::class, 'Locale must not be empty.'],
    [GeometryException::class, 'Invalid geometry dimensions.'],
    [InvalidCurrencyCodeException::class, 'Currency must be a three-letter currency code.'],
    [MustBeFiniteCurrencyValue::class, 'Currency value must be finite.'],
    [MustBeNumberException::class, 'Value must be an integer or float.'],
    [MustBeIntegerException::class, 'Value must be an integer.'],
    [MustBePositiveNumberException::class, 'Number must be greater than 0.'],
    [PythagorasException::class, 'Invalid right triangle dimensions.'],
    [RoundingException::class, 'Rounding mode must be a valid RoundingMode case or its integer equivalent.'],
    [TimeException::class, 'Time must be a non-negative finite number of seconds.'],
]);

it('preserves formatter details and the original exception', function () {
    $previous = new RuntimeException('ICU failed.');
    $exception = new CurrencyFormattingException('ICU failed.', code: 7, previous: $previous);

    expect($exception->getMessage())->toBe('Currency formatting failed: ICU failed.')
        ->and($exception->getCode())->toBe(7)
        ->and($exception->getPrevious())->toBe($previous);
});

it('provides default messages for runtime exceptions and their base hierarchy', function (string $exception, string $message) {
    $error = new $exception();

    expect($error->getMessage())->toBe($message)
        ->and(fn () => throw $error)->toThrow(BaseRuntimeException::class, $message)
        ->and(fn () => throw $error)->toThrow(RuntimeException::class, $message);
})->with([
    [BaseRuntimeException::class, 'Runtime error.'],
    [CurrencySymbolLookupException::class, 'Currency symbol lookup failed.'],
]);

it('preserves currency symbol lookup details and the original exception', function () {
    $previous = new RuntimeException('ICU failed.');
    $exception = new CurrencySymbolLookupException('ICU failed.', code: 7, previous: $previous);

    expect($exception->getMessage())->toBe('Currency symbol lookup failed: ICU failed.')
        ->and($exception->getCode())->toBe(7)
        ->and($exception->getPrevious())->toBe($previous);
});
