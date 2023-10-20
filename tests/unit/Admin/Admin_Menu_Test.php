<?php

declare( strict_types=1 );

namespace Custom_PTT\Tests\Unit\Admin;

use Exception;
use Custom_PTT\Admin\Admin_Menu;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Admin_Menu Test.
 *
 * @package Custom_PTT\Tests\Unit\Admin
 */
class Admin_Menu_Test extends TestCase {

	/**
	 * Set up.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function setUp(): void {
		parent::setUp();
		if ( ! defined( 'Custom_PTT_FILE' ) ) {
			define( 'Custom_PTT_FILE', __DIR__ . '/../../custom-post-types-taxonomies.php' );
		}
		WP_Mock::setUp();
	}

	/**
	 * Test register method.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function test_register(): void {
		$admin_menu = new Admin_Menu();

		WP_Mock::expectActionAdded( 'admin_menu', array( $admin_menu, 'register_menu' ) );

		$admin_menu->register();

		$this->assertHooksAdded();  // This asserts that the expected hooks were added
	}

	/**
	 * Test register_menu method.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function test_register_menu(): void {
		$admin_menu = new Admin_Menu();  // Instantiating the object before using it

		WP_Mock::userFunction(
			'add_menu_page',
			array(
				'times' => 1,
				'args'  => array(
					__( 'Custom PTT', 'custom-post-types-taxonomies' ),
					__( 'Custom PTT', 'custom-post-types-taxonomies' ),
					'manage_options',
					'custom-post-types-taxonomies',
					array( $admin_menu, 'render_plugin_page' ),
					'dashicons-coffee',
				),
			)
		);

		WP_Mock::userFunction(
			'add_submenu_page',
			array(
				'times' => 2,  // Since add_submenu_page is called twice in register_menu
			)
		);

		$admin_menu->register_menu();

		$this->expectNotToPerformAssertions();
	}

	/**
	 * Test render_plugin_page method.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function test_render_plugin_page(): void {
		$admin_menu = new Admin_Menu();
		ob_start();
		$admin_menu->render_plugin_page();
		$output = ob_get_clean();

		$this->assertSame( '<h1>Custom PTT</h1>', $output );
	}

	/**
	 * Test render_taxonomies_page method.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function test_render_taxonomies_page(): void {
		$admin_menu = new Admin_Menu();
		ob_start();
		$admin_menu->render_taxonomies_page();
		$output = ob_get_clean();

		$this->assertSame( '<h1>Custom Taxonomies</h1>', $output );
	}

	/**
	 * Test render_post_types_page method.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function test_render_post_types_page(): void {
		$admin_menu = new Admin_Menu();
		ob_start();
		$admin_menu->render_post_types_page();
		$output = ob_get_clean();

		$this->assertSame( '<h1>Custom Post Types</h1>', $output );
	}

	/**
	 * Tear down.
	 *
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();
		WP_Mock::tearDown();
	}
}
