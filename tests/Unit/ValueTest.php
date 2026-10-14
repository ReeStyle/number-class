<?php

use ReeStyleIt\NumberClass\Base;

it('exposes the stored value through its getter', function () {
    $value = new class('example') extends Base {};

    expect($value->get())->toBe('example');
});

it('keeps the stored value protected and exposes it through get', function () {
    $value = new class(7) extends Base {};

    expect((new ReflectionProperty(Base::class, 'value'))->isProtected())->toBeTrue()
        ->and($value->get())->toBe(7);
});

it('prevents changing an initialized readonly value', function () {
    $value = new class(7) extends Base {};
    $property = new ReflectionProperty(Base::class, 'value');

    expect(fn () => $property->setValue($value, 8))->toThrow(Error::class)
        ->and($value->get())->toBe(7);
});
