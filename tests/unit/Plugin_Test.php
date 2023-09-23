<?php

declare(strict_types=1);

namespace WordPress_Related\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WordPress_Related\Plugin;
use WordPress_Related\Plugin_Factory;

/**
 * Plugin Test.
 *
 * @package WordPress_Related\Tests\Unit
 */
class Plugin_Test extends TestCase {

	public function test_plugin_registers_services() {
		$plugin = Plugin_Factory::create();

		$this->assertInstanceOf( Plugin::class, $plugin );
	}
}
