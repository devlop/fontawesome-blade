<?php

declare(strict_types=1);

namespace Devlop\FontAwesome\Tests;

use Devlop\FontAwesome\Components\FaSolid;
use Devlop\FontAwesome\FontAwesomeComponent;
use PHPUnit\Framework\TestCase;

final class FaSolidTest extends TestCase
{
    /** @test */
    public function solid_is_instance_of_base_component() : void
    {
        $config = [
            'package' => 'node_modules/@fortawesome/fontawesome-pro',
        ];

        $this->assertInstanceOf(
            FontAwesomeComponent::class,
            new FaSolid($config['package'], 'fa-laravel')
        );
    }
}
