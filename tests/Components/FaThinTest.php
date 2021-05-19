<?php

declare(strict_types=1);

namespace Devlop\FontAwesome\Tests;

use Devlop\FontAwesome\Components\FaThin;
use Devlop\FontAwesome\FontAwesomeComponent;
use PHPUnit\Framework\TestCase;

final class FaThinTest extends TestCase
{
    /** @test */
    public function thin_is_instance_of_base_component() : void
    {
        $config = [
            'package' => 'node_modules/@fortawesome/fontawesome-pro',
        ];

        $this->assertInstanceOf(
            FontAwesomeComponent::class,
            new FaThin($config['package'], 'fa-laravel')
        );
    }
}
