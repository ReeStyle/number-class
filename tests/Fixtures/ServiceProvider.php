<?php

namespace Tests\Fixtures;

use LogicException;

/** Minimal stand-in for the optional Laravel dependency; interactions are mocked in tests. */
class ServiceProvider
{
    public function __construct(protected $app) {}

    protected function mergeConfigFrom($path, $key)
    {
        throw new LogicException('Expected mergeConfigFrom to be mocked.');
    }

    protected function publishes(array $paths, $groups = null)
    {
        throw new LogicException('Expected publishes to be mocked.');
    }
}
