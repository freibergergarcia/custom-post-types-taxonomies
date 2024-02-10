<?php

declare(strict_types=1);

namespace Custom_PTT\Tests\Unit\Unit;

use PHPUnit\Framework\TestCase;
use Custom_PTT\Plugin_Factory;
use Custom_PTT\Plugin;
use DI\Container;

/**
 * Class Plugin_Factory_Test
 *
 * @package Custom_PTT\Tests\Unit
 * @since 0.1.0-alpha
 */
class Plugin_Factory_Test extends TestCase {

	/**
	 * Test create method.
	 *
	 * @since 0.1.0-alpha
	 */
	public function test_create() {
		$container = new Container();
		$plugin    = Plugin_Factory::create( $container );

		$this->assertInstanceOf( Plugin::class, $plugin );

		// Test that the method always returns the same instance.
		$this->assertSame( $plugin, Plugin_Factory::create( $container ) );
	}
}
