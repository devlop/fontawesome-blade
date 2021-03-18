<?php

declare(strict_types=1);

namespace Devlop\FontAwesome\Components;

use Devlop\FontAwesome\FontAwesomeComponent;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class FaBrands extends FontAwesomeComponent
{
    /**
     * Create a new component instance.
     *
     * @param  string  $package
     * @param  string  $name
     * @return void
     */
    public function __construct(string $package, string $name)
    {
        parent::__construct($package, 'brands', $name);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View
    {
        return parent::render();
    }
}
