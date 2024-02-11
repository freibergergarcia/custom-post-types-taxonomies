<?php

declare( strict_types=1 );

namespace Custom_PTT\Tests\Unit;

use Custom_PTT\Container_Singleton;
use DI\Container;
use Exception;
use ReflectionClass;
use WP_UnitTestCase;

/**
 * Class Container_Singleton_Test
 *
 * @package Custom_PTT\Tests\Unit
 * @since 0.2.1
 */
class Container_Singleton_Test extends WP_UnitTestCase {

	/**
	 * Resets the container instance before each test, as it's created on plugin bootstrap
	 *
	 * @return void
	 *
	 * @since 0.2.1
	 */
	public function setUp(): void {
		parent::setUp();

		$reflection = new ReflectionClass( Container_Singleton::class );
		$container  = $reflection->getProperty( 'container' );
		$container->setAccessible( true );
		$container->setValue( null, null );
	}

	/**
	 * Test that get_instance returns a Container instance.
	 *
	 * @throws Exception
	 *
	 * @since 0.2.1
	 */
	public function test_get_instance_returns_container_instance() {
		$instance = Container_Singleton::get_instance();
		$this->assertInstanceOf( Container::class, $instance );
	}

	/**
	 * Test that get_instance always returns the same instance.
	 *
	 * @throws Exception
	 *
	 * @since 0.2.1
	 */
	public function test_get_instance_returns_same_instance() {
		$first_call_instance  = Container_Singleton::get_instance();
		$second_call_instance = Container_Singleton::get_instance();

		$this->assertSame( $first_call_instance, $second_call_instance );
	}

	/**
	 * @return void
	 *
	 * @since 0.2.1
	 */
	public function tearDown(): void {
		parent::tearDown();
	}
}
