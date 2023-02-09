<?php

declare(strict_types=1);

namespace Devlop\FontAwesome;

use Devlop\FontAwesome\Components\Brands;
use Devlop\FontAwesome\Components\Duotone;
use Devlop\FontAwesome\Components\Light;
use Devlop\FontAwesome\Components\Regular;
use Devlop\FontAwesome\Components\Solid;
use Devlop\FontAwesome\Components\Thin;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

final class FontAwesomeBladeServiceProvider extends ServiceProvider
{
    /**
     * Get the services provided by the provider.
     *
     * @return array<class-string>
     */
    public function provides() : array
    {
        return [
            //
        ];
    }

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
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'fontawesome-blade');

        $this->publishes(
            [
                $this->getConfigPath('fontawesome.php') => config_path('fontawesome.php'),
            ],
            'config',
        );

        // first check legacy config, then load the new config.
        $config = $this->app['config']->get('fontawesome-blade') ?? $this->app['config']->get('fontawesome');

        $path = array_key_exists('package', $config)
            ? rtrim($config['package'], '/') . '/svgs'
            : $config['path'];

        if (! is_string($path)) {
            throw new RuntimeException(sprintf(
                'fontawesome.path must be a string, %1$s given.',
                get_debug_type($path),
            ));
        }

        if (! is_dir($path)) {
            throw new RuntimeException(sprintf(
                '"%1$s" is not a valid Font Awesome path.',
                $path,
            ));
        }

        Blade::componentNamespace('Devlop\\FontAwesome\\Components', 'fa');

        // legacy aliases
        Blade::components([
            Solid::class => 'fa.solid',
            Regular::class => 'fa.regular',
            Light::class => 'fa.light',
            Thin::class => 'fa.thin',
            Duotone::class => 'fa.duotone',
            Brands::class => 'fa.brands',
        ]);

        $this->app->when(Solid::class)->needs('$path')->give($path);
        $this->app->when(Regular::class)->needs('$path')->give($path);
        $this->app->when(Light::class)->needs('$path')->give($path);
        $this->app->when(Thin::class)->needs('$path')->give($path);
        $this->app->when(Duotone::class)->needs('$path')->give($path);
        $this->app->when(Brands::class)->needs('$path')->give($path);
    }

    private function getConfigPath(string $fileName) : string
    {
        return realpath(__DIR__ . '/../config/' . $fileName);
    }
}
