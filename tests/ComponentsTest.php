<?php

declare(strict_types=1);

namespace Devlop\FontAwesome\Tests;

use Devlop\FontAwesome\Components\FaBrands;
use Devlop\FontAwesome\Components\FaDuotone;
use Devlop\FontAwesome\Components\FaLight;
use Devlop\FontAwesome\Components\FaRegular;
use Devlop\FontAwesome\Components\FaSolid;
use Devlop\FontAwesome\Components\FaThin;
use Devlop\FontAwesome\FontAwesomeBladeServiceProvider;
use Devlop\FontAwesome\FontAwesomeComponent;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;

final class ComponentsTest extends TestCase
{
    /**
     * Get package providers.
     *
     * @param  Application  $app
     * @return array<class-string>
     */
    protected function getPackageProviders($app) : array
    {
        return [
            FontAwesomeBladeServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     */
    protected function getEnvironmentSetUp($app) : void
    {
        $app['config']->set('fontawesome-blade.package', (function () : string {
            return realpath(__DIR__ . '/../node_modules/@fortawesome/fontawesome-pro');
        })());
    }

    /**
     * Component data provider.
     *
     * @return array<string,array<class-string>>
     */
    public function components() : array
    {
        return [
            'brands' => [FaBrands::class],
            'duotone' => [FaDuotone::class],
            'light' => [FaLight::class],
            'regular' => [FaRegular::class],
            'solid' => [FaSolid::class],
            'thin' => [FaThin::class],
        ];
    }

    /**
     * @test
     * @dataProvider components
     *
     * @param  class-string  $componentName
     * @return void
     */
    public function style_components_are_instances_of_the_base_component(string $componentName) : void
    {
        $this->assertInstanceOf(
            FontAwesomeComponent::class,
            $this->app->make($componentName, [
                'name' => 'fa-laravel',
            ]),
        );
    }

    /** @test */
    public function brands_buffer_can_be_rendered() : void
    {
        $component = $this->app->make(FaBrands::class, [
            'name' => 'buffer',
        ]);

        $this->assertIsString($this->renderComponent($component));
    }

    /** @test */
    public function duotone_zero_can_be_rendered() : void
    {
        $component = $this->app->make(FaDuotone::class, [
            'name' => '0',
        ]);

        $this->assertIsString($this->renderComponent($component));
    }

    /** @test */
    public function duotone_360_degrees_can_be_rendered() : void
    {
        $component = $this->app->make(FaDuotone::class, [
            'name' => '360-degrees',
        ]);

        $this->assertIsString($this->renderComponent($component));
    }

    /** @test */
    public function duotone_user_crown_have_the_correct_classnames() : void
    {
        $component = $this->app->make(FaDuotone::class, [
            'name' => 'user-crown',
        ]);

        $output = $this->renderComponent($component);

        $this->assertStringContainsString('class="fa-primary"', $output);
        $this->assertStringContainsString('class="fa-secondary"', $output);
    }

    /**
     * @test
     * @dataProvider components
     *
     * @param  class-string  $componentName
     * @return void
     */
    public function all_icons_can_be_rendered(string $componentName) : void
    {
        $package = $this->app['config']->get('fontawesome-blade.package');

        $style = Str::of(class_basename($componentName))
            ->after('Fa')
            ->lower();

        $path = implode('/', [
            $package,
            'svgs',
            $style,
        ]);

        $icons = (new Collection(scandir($path)))
            ->filter(function (string $icon) : bool {
                if (in_array($icon, ['.', '..'], true)) {
                    return false;
                }

                return true;
            })
            ->map(fn (string $icon) : string => Str::before($icon, '.svg'));

        foreach ($icons as $icon) {
            $component = $this->app->make($componentName, [
                'name' => $icon,
            ]);

            $this->assertIsString($this->renderComponent($component));
        }
    }

    /**
     * Render a component and return the output.
     *
     * @link https://laracasts.com/discuss/channels/laravel/testing-blade-components
     */
    private function renderComponent(FontAwesomeComponent $component) : string
    {
        return $component
            ->resolveView()
            ->with($component->data())
            ->render();
    }
}
