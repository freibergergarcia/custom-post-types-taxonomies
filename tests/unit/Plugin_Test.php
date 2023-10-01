<?php

declare( strict_types=1 );

namespace WordPress_Related\Tests\Unit;

use Exception;
use PHPUnit\Framework\TestCase;
use WordPress_Related\Plugin;
use WP_Mock;
use DI\Container;
use WordPress_Related\Infrastructure\Registerable;

/**
 * Plugin Test.
 *
 * @package WordPress_Related\Tests\Unit
 */
class Plugin_Test extends TestCase {

	/**
	 * Set up.
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		if ( ! defined( 'WORDPRESS_RELATED_FILE' ) ) {
			define( 'WORDPRESS_RELATED_FILE', __DIR__ . '/../../wordpress-related.php' );
		}
		WP_Mock::setUp();
	}

	/**
	 * Test plugin constructor.
	 *
	 * @return void
	 */
	public function test_plugin_constructor(): void {
		$container = $this->createMock( Container::class );
		$plugin    = new Plugin( $container );
		$this->assertInstanceOf( Plugin::class, $plugin );
	}

	/**
	 * Test plugin init.
	 *
	 * @return void
	 */
	public function test_plugin_init(): void {
		$container = $this->createMock( Container::class );
		$plugin    = new Plugin( $container );
		$this->assertNull( $plugin->init() );
	}

	/**
	 * Test register_hooks method.
	 *
	 * @return void
	 */
	public function test_register_hooks(): void {
		$container = $this->createMock( Container::class );
		$plugin    = new Plugin( $container );

		// Mock WordPress functions
		WP_Mock::userFunction(
			'register_activation_hook',
			array(
				'times' => 1,
				'args'  => array( WORDPRESS_RELATED_FILE, array( $plugin, 'on_activation' ) ),
			)
		);

		WP_Mock::userFunction(
			'register_deactivation_hook',
			array(
				'times' => 1,
				'args'  => array( WORDPRESS_RELATED_FILE, array( $plugin, 'on_deactivation' ) ),
			)
		);

		$plugin->register_hooks();

		$this->expectNotToPerformAssertions();
	}

	/**
	 * Test boot method.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function test_boot(): void {
		$container = $this->createMock( Container::class );
		$plugin    = new Plugin( $container );

		// Mock WordPress functions
		WP_Mock::userFunction(
			'register_activation_hook',
			array(
				'times' => 1,
				'args'  => array( WORDPRESS_RELATED_FILE, array( $plugin, 'on_activation' ) ),
			)
		);

		WP_Mock::userFunction(
			'register_deactivation_hook',
			array(
				'times' => 1,
				'args'  => array( WORDPRESS_RELATED_FILE, array( $plugin, 'on_deactivation' ) ),
			)
		);

		$container->expects( $this->once() )
					->method( 'getKnownEntryNames' )
					->willReturn( array() );

		$plugin->boot();
	}

	/**
	 * Test register_services method.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function test_register_services(): void {
		$container = $this->createMock( Container::class );
		$plugin    = new Plugin( $container );

		$registerable_mock = $this->createMock( Registerable::class );
		$registerable_mock->expects( $this->once() )
							->method( 'register' );

		$container->expects( $this->once() )
					->method( 'getKnownEntryNames' )
					->willReturn( array( 'registerableService' ) );

		$container->expects( $this->once() )
					->method( 'get' )
					->with( 'registerableService' )
					->willReturn( $registerable_mock );

		$plugin->register_services();
	}

	/**
	 * Test on_deactivation method.
	 *
	 * @return void
	 */
	public function test_on_deactivation(): void {
		$container = $this->createMock( Container::class );
		$plugin    = new Plugin( $container );

		// Mock WordPress functions
		WP_Mock::userFunction(
			'wp_cache_flush',
			array(
				'times' => 1,
			)
		);

		WP_Mock::userFunction(
			'wp_rewrite_flush',
			array(
				'times' => 1,
			)
		);

		$plugin->on_deactivation();

		$this->expectNotToPerformAssertions();
	}

	/**
	 * Tear down.
	 *
	 * @return void
	 */
	public function tearDown(): void {
		WP_Mock::tearDown();
		parent::tearDown();
	}
}
