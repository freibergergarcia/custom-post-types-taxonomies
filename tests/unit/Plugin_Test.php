<?php

declare(strict_types=1);

namespace Custom_PTT\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Custom_PTT\Plugin;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Custom_PTT\Infrastructure\Registerable;
use WP_Mock;

/**
 * Class Plugin_Test
 *
 * @package Custom_PTT\Tests\Unit
 * @since 0.1.0-alpha
 */
class Plugin_Test extends TestCase {

	/**
	 * Set up before the test class.
	 *
	 * @since 0.1.0-alpha
	 */
	public static function setUpBeforeClass(): void {
		if ( ! defined( 'CUSTOM_PTT_FILE' ) ) {
			define( 'CUSTOM_PTT_FILE', 'custom-post-types-taxonomies/custom-post-types-taxonomies.php' );
		}
	}

	/**
	 * Set up the test.
	 *
	 * @since 0.1.0-alpha
	 */
	protected function setUp(): void {
		WP_Mock::setUp();
	}

	/**
	 * Tear down the test.
	 *
	 * @since 0.1.0-alpha
	 */
	protected function tearDown(): void {
		WP_Mock::tearDown();
	}

	/**
	 * Test register_hooks method.
	 *
	 * @since 0.1.0-alpha
	 */
	public function test_register_hooks() {
		WP_Mock::userFunction( 'register_activation_hook' );
		WP_Mock::userFunction( 'register_deactivation_hook' );

		$container = $this->createMock( Container::class );
		$plugin    = new Plugin( $container );

		$this->assertNull( $plugin->register_hooks() );
	}

	/**
	 * Test on_deactivation method.
	 *
	 * @since 0.1.0-alpha
	 */
	public function test_on_deactivation() {
		WP_Mock::userFunction( 'wp_cache_flush' );
		WP_Mock::userFunction( 'flush_rewrite_rules' );

		$container = $this->createMock( Container::class );
		$plugin    = new Plugin( $container );

		$this->assertNull( $plugin->on_deactivation() );
	}

	/**
	 * Test on_activation method.
	 *
	 * @since 0.1.0-alpha
	 */
	public function test_on_activation() {
		$container = $this->createMock( Container::class );
		$plugin    = new Plugin( $container );

		$this->assertNull( $plugin->on_activation() );
	}

	/**
	 * Test on_uninstall method.
	 *
	 * @since 0.1.0-alpha
	 */
	public function test_on_uninstall() {
		$this->assertNull( Plugin::on_uninstall() );
	}

	/**
	 * Test register_services method.
	 *
	 * @since 0.1.0-alpha
	 */
	public function test_register_services() {
		$container = $this->createMock( Container::class );
		$container->method( 'getKnownEntryNames' )->willReturn( array( 'service1', 'service2' ) );

		$service1 = $this->createMock( Registerable::class );
		$service1->expects( $this->once() )->method( 'register' );

		$service2 = $this->createMock( Registerable::class );
		$service2->expects( $this->once() )->method( 'register' );

		$container->method( 'get' )->will( $this->onConsecutiveCalls( $service1, $service2 ) );

		$plugin = new Plugin( $container );
		$plugin->register_services();
	}
}
