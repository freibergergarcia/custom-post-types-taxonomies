<?php

declare( strict_types=1 );

namespace WordPress_Related\Admin;

use Exception;
use WordPress_Related\Infrastructure\Registerable;
use WordPress_Related\Taxonomy\Taxonomy_List_Table;

/**
 * Admin_Menu Class
 *
 * This class is responsible for registering and rendering the admin menu and submenus
 * for the WordPress Related plugin within the WordPress admin interface.
 * It implements the Registerable interface to ensure its registration method is called
 * during the plugin's boot process.
 *
 * @package WordPress_Related\Admin
 * @since 1.0.0
 * @version 1.0.0
 */
class Admin_Menu implements Registerable {

	/**
	 * Register the service with WordPress.
	 *
	 * Hooks the register_menu method to the admin_menu action, ensuring that
	 * the menu and its submenus are registered with WordPress at the appropriate time.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
	}

	/**
	 * Register the plugin menu.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function register_menu(): void {
		// Adding Top-level menu
		add_menu_page(
			__( 'WordPress Related', 'wordpress-related' ),
			__( 'WordPress Related', 'wordpress-related' ),
			'manage_options',
			'wordpress-related',
			array( $this, 'render_plugin_page' ),
			'dashicons-coffee'
		);

		// Adding sub-menu for Custom Taxonomies
		add_submenu_page(
			'wordpress-related',
			__( 'Custom Taxonomies', 'wordpress-related' ),
			__( 'Custom Taxonomies', 'wordpress-related' ),
			'manage_options',
			'wordpress-related-taxonomies',
			array( $this, 'render_taxonomies_page' )
		);

		// Adding sub-menu for Custom Post Types
		add_submenu_page(
			'wordpress-related',
			__( 'Custom Post Types', 'wordpress-related' ),
			__( 'Custom Post Types', 'wordpress-related' ),
			'manage_options',
			'wordpress-related-post-types',
			array( $this, 'render_post_types_page' )
		);
	}

	/**
	 * Render the main plugin page.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function render_plugin_page(): void {
		echo '<h1>' . esc_html__( 'WordPress Related', 'wordpress-related' ) . '</h1>';
	}

	/**
	 * Render the Custom Taxonomies page.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function render_taxonomies_page(): void {
		?>
		<div class="wrap">
			<h1>
				<?php echo esc_html__( 'Custom Taxonomies', 'wordpress-related' ); ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=add-wordpress-related-taxonomies' ) ); ?>"
					class="page-title-action">
					<?php echo esc_html__( 'Add New', 'wordpress-related' ); ?>
				</a>
			</h1>

			<?php
			$taxonomy_list_table = new Taxonomy_List_Table();
			$taxonomy_list_table->prepare_items();
			$taxonomy_list_table->display();
			?>
		</div>
		<?php
	}

	/**
	 * Render the Custom Post Types page.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function render_post_types_page(): void {
		echo '<h1>' . esc_html__( 'Custom Post Types', 'wordpress-related' ) . '</h1>';
	}
}
