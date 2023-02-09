<?php

declare(strict_types=1);

namespace Devlop\FontAwesome\Components;

use Devlop\FontAwesome\FontAwesomeBaseComponent;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Regular extends FontAwesomeBaseComponent
{
    /**
     * Create a new component instance.
     */
    public function __construct(string $path, string $name)
    {
        parent::__construct($path, 'regular', $name);
    }
}
