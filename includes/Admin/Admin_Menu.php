<?php

declare(strict_types=1);

namespace Custom_PTT\Admin;

use Exception;
use Custom_PTT\Infrastructure\Registerable;

/**
 * Admin_Menu Class
 *
 * This class is responsible for registering and rendering the admin menu and submenus
 * for the Custom PTT plugin within the WordPress admin interface.
 *
 * @package Custom_PTT\Admin
 * @since 0.1.0-alpha
 */
class Admin_Menu implements Registerable {

	/**
	 * Register the service with WordPress.
	 *
	 * Hooks the register_menu method to the admin_menu action, ensuring that
	 * the menu and its submenus are registered with WordPress at the appropriate time.
	 *
	 * @return void
	 *
	 * @since 0.1.0-alpha
	 */
	public function register(): void {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
	}

	/**
	 * Register the plugin menu.
	 *
	 * @return void
	 * @throws Exception
	 *
	 * @since 0.1.0-alpha
	 */
	public function register_menu(): void {
		add_menu_page(
			__( 'Custom PTT', 'custom-post-types-taxonomies' ),
			__( 'Custom PTT', 'custom-post-types-taxonomies' ),
			'manage_options',
			'custom-post-types-taxonomies',
			array( $this, 'render_taxonomies_page' ),
			'dashicons-coffee'
		);

		add_submenu_page(
			'custom-post-types-taxonomies',
			__( 'Taxonomies', 'custom-post-types-taxonomies' ),
			__( 'Taxonomies', 'custom-post-types-taxonomies' ),
			'manage_options',
			'custom-post-types-taxonomies',
			array( $this, 'render_taxonomies_page' ),
			1
		);

		add_submenu_page(
			'custom-post-types-taxonomies',
			__( 'Post Types', 'custom-post-types-taxonomies' ),
			__( 'Post Types', 'custom-post-types-taxonomies' ),
			'manage_options',
			'custom-post-types-taxonomies-post-types',
			array( $this, 'render_post_types_page' ),
			20
		);
	}

	/**
	 * Render the main plugin page.
	 *
	 * @return void
	 * @throws Exception
	 *
	 * @since 0.1.0-alpha
	 */
	public function render_plugin_page(): void {
		require __DIR__ . '/templates/custom-ptt-home.php';
	}

	/**
	 * Render the Custom Taxonomies page.
	 *
	 * @return void
	 * @throws Exception
	 *
	 * @since 0.1.0-alpha
	 */
	public function render_taxonomies_page(): void {
		require __DIR__ . '/templates/custom-ptt-taxonomies.php';
	}

	/**
	 * Render the Custom Post Types page.
	 *
	 * @return void
	 * @throws Exception
	 *
	 * @since 0.1.0-alpha
	 */
	public function render_post_types_page(): void {
		require __DIR__ . '/templates/custom-ptt-post-types.php';
	}
}
