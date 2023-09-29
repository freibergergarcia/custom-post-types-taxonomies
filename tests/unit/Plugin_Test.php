<?php

declare( strict_types=1 );

namespace WordPress_Related\Tests\Unit;

use DI\Container;
use PHPUnit\Framework\TestCase;
use WordPress_Related\Plugin;

/**
 * Plugin Test.
 *
 * @package WordPress_Related\Tests\Unit
 */
class Plugin_Test extends TestCase {

	/**
	 * Container.
	 *
	 * @since 1.0.0
	 * @var Container
	 */
	protected Container $container;

	/**
	 * Set up.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setUp(): void {
		parent::setUp();
		$this->container = $this->createMock( 'DI\Container' );
	}

	/**
	 * Test plugin constructor.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_plugin_constructor(): void {
		$plugin = new Plugin( $this->container );
		$this->assertInstanceOf( 'WordPress_Related\Plugin', $plugin );
	}

	/**
	 * Test plugin init.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_plugin_init(): void {
		$plugin = new Plugin( $this->container );
		$this->assertNull( $plugin->init() );
	}

	/**
	 * Tear down.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function tearDown(): void {
		parent::tearDown();
		unset( $this->container );
	}
}
