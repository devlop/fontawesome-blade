<?php

declare(strict_types=1);

namespace Devlop\FontAwesome\Tests;

use Devlop\FontAwesome\Components\Brands;
use Devlop\FontAwesome\Components\Duotone;
use Devlop\FontAwesome\Components\Light;
use Devlop\FontAwesome\Components\Regular;
use Devlop\FontAwesome\Components\Solid;
use Devlop\FontAwesome\Components\Thin;
use Devlop\FontAwesome\FontAwesomeBladeServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Orchestra\Testbench\TestCase;

final class RenderTest extends TestCase
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
     * (-pro) Component data provider.
     *
     * @return array<string,array<class-string>>
     */
    public static function components() : array
    {
        return [
            'fa::brands' => ['fa::brands', Brands::class],
            'fa::duotone' => ['fa::duotone', Duotone::class],
            'fa::light' => ['fa::light', Light::class],
            'fa::regular' => ['fa::regular', Regular::class],
            'fa::solid' => ['fa::solid', Solid::class],
            'fa::thin' => ['fa::thin', Thin::class],
        ];
    }

    /**
     * @test
     * @dataProvider components
     *
     * @param  class-string  $componentClassName
     */
    public function all_components_renders_properly(string $componentName, string $componentClassName) : void
    {
        $iconName = 'starship-freighter';
        $classNames = [
            'fa-fw',
            'fa-2x',
        ];

        $bladeSnippet = sprintf(
            '<x-%1$s name="%2$s" class="%3$s" />',
            $componentName,
            $iconName,
            implode(' ', $classNames),
        );

        $expectedOutputs = [
            sprintf(
                '$component = $__env->getContainer()->make(%1$s::class, [\'name\' => \'%2$s\']);',
                $componentClassName,
                $iconName,
            ),
            sprintf(
                '$component->withName(\'%1$s\');',
                $componentName,
            ),
            sprintf(
                '$component->withAttributes([\'class\' => \'%1$s\']);',
                implode(' ', $classNames),
            ),
        ];

        $actualOutput = Blade::compileString($bladeSnippet);

        foreach ($expectedOutputs as $expectedOutput) {
            $this->assertStringContainsString($expectedOutput, $actualOutput);
        }
    }
}
