<?php

declare(strict_types=1);

namespace Devlop\FontAwesome\Tests;

use Devlop\FontAwesome\Components\Brands;
use Devlop\FontAwesome\Components\Duotone;
use Devlop\FontAwesome\Components\Light;
use Devlop\FontAwesome\Components\Regular;
use Devlop\FontAwesome\Components\SharpRegular;
use Devlop\FontAwesome\Components\SharpSolid;
use Devlop\FontAwesome\Components\Solid;
use Devlop\FontAwesome\Components\Thin;
use Devlop\FontAwesome\FontAwesomeBaseComponent;
use Devlop\FontAwesome\FontAwesomeBladeServiceProvider;
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
        $app['config']->set(
            'fontawesome.path',
            realpath(__DIR__ . '/../node_modules/@fortawesome/fontawesome-pro/svgs'),
        );
    }

    /**
     * (-pro and -free) Component data provider.
     *
     * @return array<string,array<class-string>>
     */
    public static function packageComponents() : array
    {
        $freePath = realpath(__DIR__ . '/../node_modules/@fortawesome/fontawesome-free/svgs');
        $proPath = realpath(__DIR__ . '/../node_modules/@fortawesome/fontawesome-pro/svgs');

        return [
            'free-brands' => [$freePath, Brands::class],
            'free-regular' => [$freePath, Regular::class],
            'free-solid' => [$freePath, Solid::class],
            'pro-brands' => [$proPath, Brands::class],
            'pro-duotone' => [$proPath, Duotone::class],
            'pro-light' => [$proPath, Light::class],
            'pro-regular' => [$proPath, Regular::class],
            'pro-solid' => [$proPath, Solid::class],
            'pro-thin' => [$proPath, Thin::class],
            'pro-sharp-regular' => [$proPath, SharpRegular::class],
            'pro-sharp-solid' => [$proPath, SharpSolid::class],
        ];
    }

    /** @test */
    public function brands_buffer_can_be_rendered() : void
    {
        $component = $this->app->make(Brands::class, [
            'name' => 'buffer',
        ]);

        $this->assertIsString($this->renderComponent($component));
    }

    /** @test */
    public function duotone_zero_can_be_rendered() : void
    {
        $component = $this->app->make(Duotone::class, [
            'name' => '0',
        ]);

        $this->assertIsString($this->renderComponent($component));
    }

    /** @test */
    public function duotone_360_degrees_can_be_rendered() : void
    {
        $component = $this->app->make(Duotone::class, [
            'name' => '360-degrees',
        ]);

        $this->assertIsString($this->renderComponent($component));
    }

    /** @test */
    public function duotone_user_crown_have_the_correct_classnames() : void
    {
        $component = $this->app->make(Duotone::class, [
            'name' => 'user-crown',
        ]);

        $output = $this->renderComponent($component);

        $this->assertStringContainsString('class="fa-primary"', $output);
        $this->assertStringContainsString('class="fa-secondary"', $output);
    }

    /**
     * @test
     * @dataProvider packageComponents
     *
     * @param  class-string  $componentName
     */
    public function all_icons_can_be_rendered(string $path, string $componentName) : void
    {
        $style = (string) Str::of(class_basename($componentName))
            ->kebab();

        $iconsPath = implode('/', [
            $path,
            $style,
        ]);

        $icons = (new Collection(scandir($iconsPath)))
            ->filter(function (string $icon) : bool {
                $ignore = [
                    '.',
                    '..',
                ];

                return ! in_array($icon, $ignore, true);
            })
            ->values()
            ->map(fn (string $icon) : string => Str::before($icon, '.svg'));

        foreach ($icons as $icon) {
            $component = $this->app->make($componentName, [
                'path' => $path,
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
    private function renderComponent(FontAwesomeBaseComponent $component) : string
    {
        return $component
            ->resolveView()
            ->with($component->data())
            ->render();
    }
}
