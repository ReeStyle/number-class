<?php

namespace Tests\Unit\Framework\Laravel;

use ArrayObject;
use ReeStyleIt\NumberClass\Base;
use ReeStyleIt\NumberClass\Enum\Locale;
use ReeStyleIt\NumberClass\Framework\Laravel\NumberClassServiceProvider;
use Tests\Fixtures\ServiceProvider;

if (! class_exists(\Illuminate\Support\ServiceProvider::class)) {
    class_alias(ServiceProvider::class, \Illuminate\Support\ServiceProvider::class);
}

beforeEach(function () {
    $this->previousDefaultLocale = Base::getDefaultLocale();
    Base::setDefaultLocale('fr_FR');

    $this->app = new class extends ArrayObject {
        public bool $console = false;

        public function runningInConsole(): bool
        {
            return $this->console;
        }

        public function configPath(string $path): string
        {
            return '/application/config/' . $path;
        }
    };

    $this->provider = $this->getMockBuilder(NumberClassServiceProvider::class)
        ->setConstructorArgs([$this->app])
        ->onlyMethods(['mergeConfigFrom', 'publishes'])
        ->getMock();

    $this->configPath = dirname(__DIR__, 4) . '/src/Framework/Laravel/config/number-class.php';
});

afterEach(function () {
    Base::setDefaultLocale($this->previousDefaultLocale);
});

it('registers the package configuration', function () {
    $this->provider->expects($this->once())->method('mergeConfigFrom')
        ->with($this->configPath, 'number-class');

    $this->provider->register();
});

it('sets the default locale from package configuration with an application fallback', function ($packageLocale, $appLocale, $expected) {
    $this->app['config'] = new class($packageLocale, $appLocale) {
        public function __construct(private mixed $packageLocale, private ?string $appLocale) {}

        public function get(string $key): mixed
        {
            return match ($key) {
                'number-class.default_locale' => $this->packageLocale,
                'app.locale' => $this->appLocale,
            };
        }
    };
    $this->provider->expects($this->never())->method('publishes');

    $this->provider->boot();

    expect(Base::getDefaultLocale())->toBe($expected);
})->with([
    'package locale takes precedence' => ['nl_NL', 'en_US', 'nl_NL'],
    'package locale accepts an enum' => [Locale::NL_NL, 'en_US', 'nl_NL'],
    'application locale fallback' => [null, 'en_US', 'en_US'],
    'no configured locale clears the override' => [null, null, null],
]);

it('publishes the package configuration when running in the console', function () {
    $this->app->console = true;
    $this->app['config'] = new class {
        public function get(string $key): ?string
        {
            return null;
        }
    };
    $this->provider->expects($this->once())->method('publishes')
        ->with([$this->configPath => '/application/config/number-class.php'], 'number-class-config');

    $this->provider->boot();
});
