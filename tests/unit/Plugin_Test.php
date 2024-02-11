<?php

declare( strict_types=1 );

namespace Custom_PTT\Tests\Unit;

use Custom_PTT\Container_Singleton;
use Custom_PTT\Plugin;
use Custom_PTT\Plugin_Factory;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use WP_UnitTestCase;

/**
 * Class Plugin_Test
 *
 * @package Custom_PTT\Tests\Unit
 * @since 0.1.0-alpha
 */
class Plugin_Test extends WP_UnitTestCase {

	/**
	 * Container instance.
	 *
	 * @var Container
	 *
	 * @since 0.2.1
	 */
	private Container $container;

	/**
	 * Plugin instance.
	 *
	 * @var Plugin
	 *
	 * @since 0.2.1
	 */
	private Plugin $plugin;

	/**
	 * Set up
	 *
	 * @return void
	 *
	 * @throws Exception
	 * @since 0.1.0-alpha
	 */
	public function setUp(): void {
		parent::setUp();

		$this->container = Container_Singleton::get_instance();
		$this->plugin    = Plugin_Factory::create( $this->container );
	}

	/**
	 * Test plugin instance is created
	 *
	 * @return void
	 *
	 * @since 0.2.1
	 */
	public function test_plugin_instance_is_created() {
		$this->assertInstanceOf( Plugin::class, $this->plugin );
	}

	/**
	 * Test boot register activation and deactivation hooks
	 *
	 * @throws Exception
	 *
	 * @since 0.2.1
	 */
	public function test_boot_method_registers_hooks() {

		$this->plugin->boot();

		$file = plugin_basename( CUSTOM_PTT_FILE );

		$this->assertEquals( 10, has_action( 'activate_' . $file, array( $this->plugin, 'on_activation' ) ) );
		$this->assertEquals( 10, has_action( 'deactivate_' . $file, array( $this->plugin, 'on_deactivation' ) ) );
	}

	/**
	 * Test all services are registered
	 *
	 * @since 0.2.1
	 */
	public function test_register_services_method_registers_services() {
		$services = $this->container->getKnownEntryNames();

		if ( empty( $services ) ) {
			$this->fail( 'No services found' );
		}

		foreach ( $services as $service ) {
			$this->assertTrue( $this->container->has( $service ) );
		}
	}

	/**
	 * Tear down
	 *
	 * @return void
	 *
	 * @since 0.1.0-alpha
	 */
	public function tearDown(): void {
		parent::tearDown();
	}
}
