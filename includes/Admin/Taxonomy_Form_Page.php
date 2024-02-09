<?php

declare(strict_types=1);

namespace Custom_PTT\Admin;

use Exception;
use Custom_PTT\Infrastructure\Registerable;
use Custom_PTT\Utilities;
use WP_Taxonomy;

/**
 * Taxonomy_Form_Page Class
 *
 * This class is responsible for rendering and handling the taxonomy creation form.
 *
 * @package Custom_PTT\Taxonomy
 * @since 0.1.0-alpha
 */
class Taxonomy_Form_Page implements Registerable {

	use Utilities;

	/**
	 * The name of the option used to store the taxonomy settings.
	 *
	 * @since 0.1.0-alpha
	 */
	public const OPTION_NAME = 'custom_ptt_taxonomy_settings';

	/**
	 * Register the service with WordPress.
	 * Hooks the render_form method to the appropriate action.
	 *
	 * @return void
	 *
	 * @since 0.1.0-alpha
	 */
	public function register(): void {
		add_action( 'admin_menu', array( $this, 'add_form_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Enqueue assets.
	 *
	 * @return void
	 *
	 * @since 0.1.0-alpha
	 */
	public function enqueue_assets(): void {
		wp_enqueue_style( 'custom-ptt-admin', plugin_dir_url( CUSTOM_PTT_FILE ) . 'assets/css/custom-ptt.css', array(), '1.0.0' );
		wp_enqueue_script( 'custom-ptt-admin', plugin_dir_url( CUSTOM_PTT_FILE ) . 'assets/js/custom-ptt.js', array( 'jquery' ), '1.0.0', true );
	}

	/**
	 * Add the form page to the WordPress admin menu.
	 *
	 * @return void
	 * @throws Exception
	 *
	 * @since 0.1.0-alpha
	 */
	public function add_form_page(): void {
		add_submenu_page(
			'custom-post-types-taxonomies',
			__( 'Add New Taxonomy', 'custom-post-types-taxonomies' ),
			__( 'Add New Taxonomy ', 'custom-post-types-taxonomies' ),
			'manage_options',
			'add-custom-post-types-taxonomies-taxonomies',
			array( $this, 'render_form' ),
			1
		);
	}

	/**
	 * Render the taxonomy creation form.
	 *
	 * @since 0.1.0-alpha
	 * @return void
	 * @throws Exception
	 */
	public function render_form(): void {
		$taxonomy_name = isset( $_GET['taxonomy'] ) ? sanitize_text_field( $_GET['taxonomy'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$taxonomy_data = null;
		$taxonomy      = get_taxonomy( $taxonomy_name );

		if ( $taxonomy instanceof WP_Taxonomy ) {
			$taxonomies    = get_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME, array() );
			$taxonomy_data = $taxonomies[ $taxonomy->name ] ?? null;
		}

		require __DIR__ . '/templates/custom-ptt-taxonomies-form.php';
	}
}
