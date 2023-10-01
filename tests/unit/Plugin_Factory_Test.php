<?php

declare( strict_types=1 );

namespace WordPress_Related\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WordPress_Related\Plugin_Factory;
use DI\Container;

/**
 * Plugin Factory Test.
 *
 * @package WordPress_Related\Tests\Unit
 */
class Plugin_Factory_Test extends TestCase {

	/**
	 * Test plugin factory create.
	 *
	 * @return void
	 */
	public function test_plugin_factory_create(): void {
		$container = $this->createMock( Container::class );

		$plugin_instance_one = Plugin_Factory::create( $container );
		$plugin_instance_two = Plugin_Factory::create( $container );

		$this->assertInstanceOf( 'WordPress_Related\Plugin', $plugin_instance_one );
		$this->assertInstanceOf( 'WordPress_Related\Plugin', $plugin_instance_two );
		$this->assertSame( $plugin_instance_one, $plugin_instance_two );
	}
}
