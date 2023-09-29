<?php

declare( strict_types=1 );

namespace WordPress_Related\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WordPress_Related\Plugin;
use WordPress_Related\Plugin_Factory;
use DI\Container;

/**
 * Plugin Factory Test.
 *
 * @package WordPress_Related\Tests\Unit
 */
class Plugin_Factory_Test extends TestCase {

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
	 * Test plugin factory create.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_plugin_factory_create(): void {
		$plugin = Plugin_Factory::create( $this->container );
		$this->assertInstanceOf( 'WordPress_Related\Plugin', $plugin );
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
