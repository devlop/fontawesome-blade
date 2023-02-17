<?php

declare(strict_types=1);

namespace Devlop\FontAwesome;

use Devlop\FontAwesome\Components\Brands;
use Devlop\FontAwesome\Components\Duotone;
use Devlop\FontAwesome\Components\Light;
use Devlop\FontAwesome\Components\Regular;
use Devlop\FontAwesome\Components\SharpRegular;
use Devlop\FontAwesome\Components\SharpSolid;
use Devlop\FontAwesome\Components\Solid;
use Devlop\FontAwesome\Components\Thin;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

final class FontAwesomeBladeServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register() : void
    {
        $this->mergeConfigFrom($this->getConfigPath('fontawesome.php'), 'fontawesome');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot() : void
    {
        $this->loadViewsFrom(realpath(__DIR__ . '/../resources/views'), 'fontawesome-blade');

        $this->publishes(
            [
                $this->getConfigPath('fontawesome.php') => config_path('fontawesome.php'),
            ],
            'config',
        );

        $path = $this->app['config']->get('fontawesome.path');

        if (! is_string($path)) {
            throw new RuntimeException(sprintf(
                'fontawesome.path must be a string, %1$s given.',
                get_debug_type($path),
            ));
        }

        Blade::componentNamespace('Devlop\\FontAwesome\\Components', 'fa');

        $this->app
            ->when([
                Brands::class,
                Duotone::class,
                Light::class,
                Regular::class,
                Solid::class,
                Thin::class,
                SharpRegular::class,
                SharpSolid::class,
            ])
            ->needs('$path')
            ->give($path);
    }

    private function getConfigPath(string $fileName) : string
    {
        return realpath(__DIR__ . '/../config/' . $fileName);
    }
}
