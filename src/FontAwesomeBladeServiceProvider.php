<?php

declare(strict_types=1);

namespace Devlop\FontAwesome;

use Devlop\FontAwesome\Components\FaBrands;
use Devlop\FontAwesome\Components\FaDuotone;
use Devlop\FontAwesome\Components\FaLight;
use Devlop\FontAwesome\Components\FaRegular;
use Devlop\FontAwesome\Components\FaSolid;
use Devlop\FontAwesome\Components\FaThin;
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
        $this->mergeConfigFrom($this->getConfigPath(), 'fontawesome-blade');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot() : void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'fontawesome-blade');

        $this->publishes(
            [
                $this->getConfigPath() => config_path('fontawesome-blade.php'),
            ],
            'config',
        );

        $config = $this->app['config']->get('fontawesome-blade');

        if (! is_string($config['package']) || $config['package'] === '') {
            throw new RuntimeException('No Font Awesome package path configured.');
        }

        Blade::components([
            FaSolid::class => 'fa.solid',
            FaRegular::class => 'fa.regular',
            FaLight::class => 'fa.light',
            FaThin::class => 'fa.thin',
            FaDuotone::class => 'fa.duotone',
            FaBrands::class => 'fa.brands',
        ]);

        $this->app->when(FaSolid::class)->needs('$package')->give($config['package']);
        $this->app->when(FaRegular::class)->needs('$package')->give($config['package']);
        $this->app->when(FaLight::class)->needs('$package')->give($config['package']);
        $this->app->when(FaThin::class)->needs('$package')->give($config['package']);
        $this->app->when(FaDuotone::class)->needs('$package')->give($config['package']);
        $this->app->when(FaBrands::class)->needs('$package')->give($config['package']);
    }

    /**
     * Get the speedtrap config path
     */
    private function getConfigPath() : string
    {
        return __DIR__ . '/../config/fontawesome-blade.php';
    }
}
