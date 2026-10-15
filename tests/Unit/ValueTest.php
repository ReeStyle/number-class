<?php

use ReeStyleIt\NumberClass\Base;

it('exposes the stored value through its getter', function () {
    $value = new class('example') extends Base {};

    expect($value->get())->toBe('example');
});

it('exposes the stored value as a public readonly property', function () {
    $value = new class(7) extends Base {};

    expect($value->value)->toBe(7)
        ->and($value->get())->toBe(7);
});

it('prevents changing an initialized readonly value', function () {
    $value = new class(7) extends Base {};
    expect(function () use ($value) {
        $value->value = 8;
    })->toThrow(Error::class)
        ->and($value->get())->toBe(7);
});
