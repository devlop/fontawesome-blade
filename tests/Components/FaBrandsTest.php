<?php

declare(strict_types=1);

namespace Devlop\FontAwesome\Tests;

use Devlop\FontAwesome\Components\FaBrands;
use Devlop\FontAwesome\FontAwesomeComponent;
use PHPUnit\Framework\TestCase;

final class FaBrandsTest extends TestCase
{
    public function test_brands_is_instance_of_base_component() : void
    {
        $config = [
            'package' => 'node_modules/@fortawesome/fontawesome-pro',
        ];

        $this->assertInstanceOf(
            FontAwesomeComponent::class,
            new FaBrands($config['package'], 'fa-laravel')
        );
    }
}
