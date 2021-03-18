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
            'path' => $svg['path'],
        ]);
    }

    /**
     * Get the svg contents
     */
    private function getSvg(string $package, string $style, string $name) : array
    {
        $path = $this->getSvgPath($package, $style, $name);

        $contents = file_get_contents($path);

        $match = null;

        if (! preg_match('~<svg .+viewBox="(?P<viewBox>0 0 (?P<width>\d+) (?P<height>\d+))"><!--.+--><path d="(?P<path>[^"]+)"/></svg>~', $contents, $match)) {
            throw new \RuntimeException('Invalid icon, failed to parse svg');
        }

        return [
            'viewBox' => $match['viewBox'],
            'width' => (int) $match['width'],
            'height' => (int) $match['height'],
            'path' => $match['path'],
        ];
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
