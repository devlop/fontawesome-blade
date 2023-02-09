<?php

declare(strict_types=1);

namespace Devlop\FontAwesome\Components;

use Devlop\FontAwesome\FontAwesomeBaseComponent;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Thin extends FontAwesomeBaseComponent
{
    public function __construct(string $path, string $name)
    {
        parent::__construct($path, 'thin', $name);
    }
}
