<?php

namespace ReeStyleIt\NumberClass\Framework\Laravel;

use Illuminate\Support\ServiceProvider;
use ReeStyleIt\NumberClass\Base;

class NumberClassServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/number-class.php', 'number-class');
    }

    public function boot(): void
    {
        Base::setDefaultLocale(
            $this->app['config']->get('number-class.default_locale')
                ?? $this->app['config']->get('app.locale')
        );

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/number-class.php' => $this->app->configPath('number-class.php'),
            ], 'number-class-config');
        }
    }
}
