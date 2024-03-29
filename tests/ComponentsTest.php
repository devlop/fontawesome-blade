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
use DirectoryIterator;
use Illuminate\Contracts\Foundation\Application;
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
     * @dataProvider caseInsensitiveOptionalPrefixedNamesProvider
     *
     * @param  class-string  $componentName
     */
    public function names_are_case_insensitive_and_can_use_fa_dash_prefix_and_unprefixed(string $componentName, string $iconName) : void
    {
        $component = $this->app->make($componentName, [
            'name' => $iconName,
        ]);

        $this->assertIsString($this->renderComponent($component));
    }

    public function caseInsensitiveOptionalPrefixedNamesProvider() : array
    {
        $generatePermutations = function (string $componentClassName, array $iconNamePermutations) : array {
            $permutations = [];

            foreach ($iconNamePermutations as $iconNamePermutation) {
                $key = Str::kebab(class_basename($componentClassName)) . ' ' . $iconNamePermutation;

                $permutations[$key] = [
                    $componentClassName,
                    $iconNamePermutation,
                ];
            }

            return $permutations;
        };

        $iconNamePermutations = ['alien', 'Alien', 'ALIEN', 'alIEn', 'fa-alien', 'FA-ALIEN', 'Fa-AlieN'];

        return array_merge(
            $generatePermutations(Brands::class, ['hooli', 'Hooli', 'HOOLI', 'hoOLi', 'fa-hooli', 'FA-HOOLI', 'Fa-HoolI']),
            $generatePermutations(Duotone::class, $iconNamePermutations),
            $generatePermutations(Light::class, $iconNamePermutations),
            $generatePermutations(Regular::class, $iconNamePermutations),
            $generatePermutations(Solid::class, $iconNamePermutations),
            $generatePermutations(Thin::class, $iconNamePermutations),
            $generatePermutations(SharpRegular::class, $iconNamePermutations),
            $generatePermutations(SharpSolid::class, $iconNamePermutations),
        );

        return $datasets;
    }

    /**
     * @test
     * @dataProvider packageComponentsProvider
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

        foreach (new DirectoryIterator($iconsPath) as $icon) {
            if ($icon->isDot()) {
                continue;
            }

            $iconName = Str::before($icon->getFileName(), '.svg');

            $component = $this->app->make($componentName, [
                'path' => $path,
                'name' => $iconName,
            ]);

            $this->assertIsString($this->renderComponent($component));
        }
    }

    /**
     * (-pro and -free) Component data provider.
     *
     * @return array<string,array<class-string>>
     */
    public static function packageComponentsProvider() : array
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
