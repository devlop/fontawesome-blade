<?php

declare(strict_types=1);

namespace Devlop\FontAwesome\Tests;

use Devlop\FontAwesome\Components\FaRegular;
use Devlop\FontAwesome\FontAwesomeComponent;
use PHPUnit\Framework\TestCase;

final class FaRegularTest extends TestCase
{
    /** @test */
    public function regular_is_instance_of_base_component() : void
    {
        $config = [
            'package' => 'node_modules/@fortawesome/fontawesome-pro',
        ];

        $this->assertInstanceOf(
            FontAwesomeComponent::class,
            new FaRegular($config['package'], 'fa-laravel')
        );
    }
}
