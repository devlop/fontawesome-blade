<?php

declare(strict_types=1);

namespace Devlop\FontAwesome\Tests;

use Devlop\FontAwesome\Components\FaLight;
use Devlop\FontAwesome\FontAwesomeComponent;
use PHPUnit\Framework\TestCase;

final class FaLightTest extends TestCase
{
    /** @test */
    public function light_is_instance_of_base_component() : void
    {
        $config = [
            'package' => 'node_modules/@fortawesome/fontawesome-pro',
        ];

        $this->assertInstanceOf(
            FontAwesomeComponent::class,
            new FaLight($config['package'], 'fa-laravel')
        );
    }
}
