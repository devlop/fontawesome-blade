<?php

declare(strict_types=1);

use Devlop\FontAwesome\Components\Brands;
use Devlop\FontAwesome\Components\Duotone;
use Devlop\FontAwesome\Components\Light;
use Devlop\FontAwesome\Components\Regular;
use Devlop\FontAwesome\Components\SharpRegular;
use Devlop\FontAwesome\Components\SharpSolid;
use Devlop\FontAwesome\Components\Solid;
use Devlop\FontAwesome\Components\Thin;
use Devlop\FontAwesome\FontAwesomeBladeServiceProvider;
use Illuminate\Support\Facades\Blade;
use Orchestra\Testbench\TestCase;

final class FontAwesomeBladeServiceProviderTest extends TestCase
{
    /** @test */
    public function registers_fa_component_namespace() : void
    {
        $this->app->make('config')->set([
            'fontawesome' => require realpath(rtrim(__DIR__, '/') . '/../config/fontawesome.php'),
        ]);

        $provider = new FontAwesomeBladeServiceProvider($this->app);

        $provider->boot();

        $namespaces = Blade::getClassComponentNamespaces();

        $this->assertArrayHasKey('fa', $namespaces);
        $this->assertSame('Devlop\\FontAwesome\\Components', $namespaces['fa']);
    }

    /**
     * @test
     * @dataProvider componentsProvider
     *
     * @param  class-string  $componentClassName
     */
    public function injects_path_on_components(string $componentClassName) : void
    {
        $this->app->make('config')->set([
            'fontawesome' => require realpath(rtrim(__DIR__, '/') . '/../config/fontawesome.php'),
        ]);

        $provider = new FontAwesomeBladeServiceProvider($this->app);

        $provider->boot();

        $contextualBindings = $this->app->contextual;

        $expectedPath = $this->app->make('config')->get('fontawesome.path');

        $this->assertArrayHasKey($componentClassName, $contextualBindings);
        $this->assertArrayHasKey('$path', $contextualBindings[$componentClassName]);
        $this->assertSame($expectedPath, $contextualBindings[$componentClassName]['$path']);
    }

    /**
     * (-pro) Component data provider.
     *
     * @return array<string,array<class-string>>
     */
    public static function componentsProvider() : array
    {
        return [
            'brands' => [Brands::class],
            'duotone' => [Duotone::class],
            'light' => [Light::class],
            'regular' => [Regular::class],
            'solid' => [Solid::class],
            'thin' => [Thin::class],
            'sharp-regular' => [SharpRegular::class],
            'sharp-solid' => [SharpSolid::class],
        ];
    }
}
