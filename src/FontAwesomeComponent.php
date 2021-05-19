<?php

declare(strict_types=1);

namespace Devlop\FontAwesome;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

abstract class FontAwesomeComponent extends Component
{
    private string $defaultReplacementClass = 'svg-inline--fa';

    /**
     * The package containing the icons
     */
    private string $package;

    /**
     * The style to render
     */
    private string $style;

    /**
     * The icon name
     */
    private string $name;

    /**
     * Create a new component instance.
     *
     * @param  string  $package
     * @param  string  $style
     * @param  string  $name
     * @return void
     */
    protected function __construct(string $package, string $style, string $name)
    {
        $this->package = $package;

        $this->style = $style;

        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View
    {
        $svg = $this->getSvg($this->package, $this->style, $this->name);

        $inlineSvgClasses = $this->getInlineSvgClasses($this->name, $svg['width'], $svg['height']);

        return view('fontawesome-blade::components.fa-icon', [
            // 'name' => $this->name,
            'viewBox' => $svg['viewBox'],
            'inlineSvgClasses' => $inlineSvgClasses,
            'style' => $svg['style'],
            'paths' => $svg['paths'],
        ]);
    }

    /**
     * Get the svg contents
     */
    private function getSvg(string $package, string $style, string $name) : array
    {
        $path = $this->getSvgPath($package, $style, $name);

        $contents = file_get_contents($path);

        $svg = $this->parseSvg($contents);

        if (! $svg) {
            dd($contents);
            throw new \RuntimeException(sprintf(
                'Invalid icon "%1$s", failed to parse svg.',
                $name,
            ));
        }

        return $svg;
    }

    /**
     * Get the path to the svg
     */
    private function getSvgPath(string $package, string $style, string $name) : string
    {
        return sprintf(
            '%1$s/svgs/%2$s/%3$s.svg',
            rtrim($package, '/'),
            $style,
            Str::after($name, 'fa-'),
        );
    }

    /**
     * Parse the svg.
     *
     * @return array<string,int|string|array<array<string,string>>>
     */
    private function parseSvg(string $contents) : ?array
    {
        $svgMatch = null;
        $pathsMatches = null;

        if (! preg_match($this->getSvgRegex(), $contents, $svgMatch)) {
            return null;
        }

        if (! preg_match_all($this->getPathsRegex(), $svgMatch['paths'], $pathsMatches, \PREG_SET_ORDER)) {
            return null;
        }

        $paths = array_map(function (array $path) : array {
            return [
                'class' => $path['class'],
                'd' => $path['d'],
            ];
        }, $pathsMatches);

        return [
            'viewBox' => $svgMatch['viewBox'],
            'width' => (int) $svgMatch['width'],
            'height' => (int) $svgMatch['height'],
            'style' => $svgMatch['style'],
            'paths' => $paths,
        ];
    }

    /**
     * Get the svg regex pattern.
     */
    private function getSvgRegex() : string
    {
        return '~^
            <svg\s.+viewBox="(?P<viewBox>0\s0\s(?P<width>\d+)\s(?P<height>\d+))">   # opening <svg> with width and height (from viewBox attribute)
            <!--.+-->                                                               # Font Awesome License Comment
            (?:                                                                     # optional style definitions, used by duotone
                <defs>
                    <style>
                        (?P<style>.+)                                               # capture group "styles" for the styles
                    </style>
                </defs>
            )?
            (?P<paths>                                                              # capture group "paths"
                (?:
                    <path                                                           # opening <path>
                        \s.+?                                                       # path contents
                    (?:/>|></path>)                                                 # either self closing /> or closing </path>
                ){1,2}                                                              # match 1-2 <path>
            )
            </svg>                                                                  # closing </svg>
        $~x';
    }

    /**
     * Get the <path> regex pattern.
     */
    private function getPathsRegex() : string
    {
        return '~
            <path                                       # opening <path>
                (?:\sclass="(?P<class>[^"]+)")?         # optional classes
                \sd="(?P<d>[^"]+)"                      # the path definition "d" attribute
                .*?                                     # ignore any un-needed information after "d" attribute
            (?:/>|></path>)                             # either self closing /> or closing </path>
        ~x';
    }

    /**
     * Get the css classes needed to display the svg inline
     * This is meant to mimic how fa's js->svg works
     *
     * @link https://fontawesome.com/how-to-use/on-the-web/advanced/svg-javascript-core
     */
    private function getInlineSvgClasses(string $name, int $width, int $height) : string
    {
        return implode(' ', [
            $this->defaultReplacementClass,
            // $name,
            'fa-w-' . ceil($width / $height * 16), // widthClass
        ]);
    }
}
