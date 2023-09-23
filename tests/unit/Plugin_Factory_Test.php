<?php

declare(strict_types=1);

namespace WordPress_Related\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WordPress_Related\Plugin;
use WordPress_Related\Plugin_Factory;

/**
 * Plugin Factory Test.
 *
 * @package WordPress_Related\Tests\Unit
 */
class Plugin_Factory_Test extends TestCase {

	public function test_plugin_factory_creates_plugin_instance() {
		$plugin = Plugin_Factory::create();

		$this->assertInstanceOf( Plugin::class, $plugin );
	}

	public function test_plugin_factory_creates_shared_instance() {
		$plugin = Plugin_Factory::create();

		$this->assertSame( $plugin, Plugin_Factory::create() );
	}
}
