<?php

declare( strict_types=1 );

namespace Custom_PTT\Admin;

use Custom_PTT\Infrastructure\Registerable;
use Exception;
use WP_Error, WP_Taxonomy;

/**
 * Taxonomy_Form_Handler Class
 *
 * This class is responsible for handling the submission of the taxonomy creation form.
 * It implements the Registerable interface to ensure its registration method is called
 * during the plugin's boot process.
 *
 * @package Custom_PTT\Admin
 * @since 0.1.0-alpha
 */
class Taxonomy_Form_Handler implements Registerable {

	/**
	 * Registers the handle_form_submission method to the appropriate WordPress hook.
	 *
	 * @since 0.1.0-alpha
	 * @return void
	 */
	public function register(): void {
		add_filter( 'check_admin_referer', '__return_true' );
		add_action( 'admin_post_custom_ptt_save_taxonomy', array( $this, 'handle_form_submission' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}

	/**
	 * Handles the form submission.
	 *
	 * Validates the input, saves the data if valid, and provides feedback to the user.
	 *
	 * @since 0.1.0-alpha
	 * @return void
	 * @throws Exception
	 */
	public function handle_form_submission(): void {

		// Verify custom nonce custom_ptt_taxonomy_nonce
		if (
			! isset( $_POST['custom_ptt_taxonomy_nonce'] )
			|| ! wp_verify_nonce( $_POST['custom_ptt_taxonomy_nonce'], 'custom_ptt_save_taxonomy' )
			|| ! check_admin_referer( 'custom_ptt_save_taxonomy', 'custom_ptt_taxonomy_nonce' )
		) {
			wp_die( esc_html__( 'Security check failed. Please try again.', 'custom-post-types-taxonomies' ) );
		}

		// Sanitize and validate input
		$data = array(
			'taxonomy_slug'  => isset( $_POST['taxonomy-slug'] ) ? sanitize_text_field( wp_unslash( $_POST['taxonomy-slug'] ) ) : '',
			'plural_label'   => isset( $_POST['plural-label'] ) ? sanitize_text_field( wp_unslash( $_POST['plural-label'] ) ) : '',
			'singular_label' => isset( $_POST['singular-label'] ) ? sanitize_text_field( wp_unslash( $_POST['singular-label'] ) ) : '',
			'post_type'      => isset( $_POST['post-type'] ) && is_array( $_POST['post-type'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['post-type'] ) ) : array(),
		);

		try {
			$taxonomy = $this->register_taxonomy( $data );
			if ( $taxonomy instanceof WP_Error ) {
				throw new Exception( $taxonomy->get_error_message() );
			}

			$store_taxonomy = $this->store_taxonomy( $data );
			if ( false === $store_taxonomy ) {
				throw new Exception( esc_html__( 'An error occurred while saving the taxonomy.', 'custom-post-types-taxonomies' ) );
			}

			wp_redirect( admin_url( 'admin.php?page=custom-post-types-taxonomies-taxonomies&status=success' ) );

		} catch ( Exception $e ) {
			wp_redirect( admin_url( 'admin.php?page=custom-post-types-taxonomies-taxonomies&status=error' ) );
		}
		exit;
	}

	/**
	 * Registers the taxonomy.
	 *
	 * @param array $data The data to use when registering the taxonomy.
	 *
	 * @since 0.1.0-alpha
	 * @return WP_Error|WP_Taxonomy
	 */
	private function register_taxonomy( array $data ): WP_Error|WP_Taxonomy {
		$labels = array(
			'name'          => $data['plural_label'],
			'singular_name' => $data['singular_label'],
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_in_nav_menus' => true,
			'show_in_rest'      => true,
		);

		return register_taxonomy( $data['taxonomy_slug'], $data['post_type'], $args );
	}

	/**
	 * Stores the taxonomy in the options table.
	 *
	 * @param array $data The data to use when storing the taxonomy.
	 *
	 * @since 0.1.0-alpha
	 * @return bool True if the option was updated successfully, false otherwise.
	 */
	private function store_taxonomy( array $data ): bool {
		$taxonomies                           = get_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME, array() );
		$taxonomies[ $data['taxonomy_slug'] ] = $data;

		return update_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME, $taxonomies );
	}

	/**
	 * Enqueues the plugin's styles on the specific admin page.
	 *
	 * @since 0.1.0-alpha
	 * @return void
	 */
	public function enqueue_styles(): void {
		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'custom-ptt_page_add-custom-post-types-taxonomies-taxonomies' === $screen->id ) {
			wp_enqueue_style(
				'custom-ptt-general',
				CUSTOM_PTT_URL . './assets/css/public/general.css',
				array(),
				'1.0.0',
				'all'
			);
		}
	}
}
