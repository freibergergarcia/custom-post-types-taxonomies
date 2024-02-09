<?php

declare(strict_types=1);

namespace Custom_PTT\Admin;

use Exception;
use Custom_PTT\Infrastructure\Registerable;
use Custom_PTT\Utilities;
use WP_Post_Type;

/**
 * Post_Type_Form_Page Class
 *
 * This class is responsible for rendering and handling the post type creation form.
 *
 * @package Custom_PTT\Post_Type
 * @since 0.1.0-alpha
 */
class Post_Type_Form_Page implements Registerable {

	use Utilities;

	/**
	 * The name of the option used to store the post type settings.
	 *
	 * @since 0.1.0-alpha
	 */
	public const OPTION_NAME = 'custom_ptt_post_type_settings';

	/**
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
	 * Enqueue assets
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
			__( 'Add New Post Type', 'custom-post-types-taxonomies' ),
			__( 'Add New Post Type ', 'custom-post-types-taxonomies' ),
			'manage_options',
			'add-custom-post-types-taxonomies-post-types',
			array( $this, 'render_form' ),
			20
		);
	}

	/**
	 * Render the post type creation form.
	 *
	 * @return void
	 * @throws Exception
	 *
	 * @since 0.1.0-alpha
	 */
	public function render_form(): void {
		$post_type_name = isset( $_GET['custom_post_type'] ) ? sanitize_text_field( $_GET['custom_post_type'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$post_type_data = null;
		$post_type      = get_post_type_object( $post_type_name );
		$taxonomies     = get_taxonomies( array( '_builtin' => false ) );

		if ( $post_type instanceof WP_Post_Type ) {
			$post_types     = get_option( CUSTOM_PTT_POST_TYPE_OPTION_NAME, array() );
			$post_type_data = $post_types[ $post_type->name ] ?? null;
		}

		require __DIR__ . '/templates/custom-ptt-post-types-form.php';
	}
}
