<?php

declare( strict_types=1 );

namespace Custom_PTT\Admin;

use Custom_PTT\Infrastructure\Registerable;
use Exception;
use WP_Error;
use WP_Post_Type;

/**
 * Post_Type_Form_Handler Class
 *
 * This class is responsible for handling the submission of the post type creation form.
 * It implements the Registerable interface to ensure its registration method is called
 * during the plugin's boot process.
 *
 * @package Custom_PTT\Admin
 * @since 0.1.0-alpha
 */
class Post_Type_Form_Handler implements Registerable {

	/**
	 * Registers the handle_form_submission method to the appropriate WordPress hook.
	 *
	 * @since 0.1.0-alpha
	 * @return void
	 */
	public function register(): void {
		add_filter( 'check_admin_referer', '__return_true' );
		add_action( 'admin_post_custom_ptt_save_post_type', array( $this, 'handle_form_submission' ) );
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
		if (
			! isset( $_POST['custom_ptt_post_type_nonce'] )
			|| ! wp_verify_nonce( $_POST['custom_ptt_post_type_nonce'], 'custom_ptt_save_post_type' )
			|| ! check_admin_referer( 'custom_ptt_save_post_type', 'custom_ptt_post_type_nonce' )
		) {
			wp_die( esc_html__( 'Security check failed. Please try again.', 'custom-post-types-taxonomies' ) );
		}

		$data = array(
			'post_type_key'  => isset( $_POST['post-type-key'] ) ? sanitize_text_field( wp_unslash( $_POST['post-type-key'] ) ) : '',
			'plural_label'   => isset( $_POST['plural-label'] ) ? sanitize_text_field( wp_unslash( $_POST['plural-label'] ) ) : '',
			'singular_label' => isset( $_POST['singular-label'] ) ? sanitize_text_field( wp_unslash( $_POST['singular-label'] ) ) : '',
			'post_type_slug' => isset( $_POST['post-type-slug'] ) ? sanitize_text_field( wp_unslash( $_POST['post-type-slug'] ) ) : '',
			'taxonomies'     => isset( $_POST['taxonomies'] ) && is_array( $_POST['taxonomies'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['taxonomies'] ) ) : array(),
		);

		try {
			$post_type = $this->register_post_type( $data );
			if ( $post_type instanceof WP_Error ) {
				throw new Exception( $post_type->get_error_message() );
			}

			$store_post_type = $this->store_post_type( $data );
			if ( ! array_key_exists( $data['post_type_key'], $store_post_type ) ) {
				throw new Exception( esc_html__( 'An error occurred while saving the post type.', 'custom-post-types-taxonomies' ) );
			}

			Notices::add_admin_notice(
				'success',
				esc_html__( 'Post Type updated successfully.', 'custom-post-types-taxonomies' )
			);
			wp_redirect( admin_url( 'admin.php?page=custom-post-types-taxonomies-post-types&status=success' ) );

		} catch ( Exception $e ) {
			Notices::add_admin_notice(
				'error',
				$e->getMessage()
			);
			wp_redirect( admin_url( 'admin.php?page=custom-post-types-taxonomies-post-types&status=error' ) );
		}
		exit;
	}

	/**
	 * Registers a custom post type.
	 *
	 * @param array $data The data to use when registering the post type.
	 *
	 * @since 0.1.0-alpha
	 * @return WP_Post_Type|WP_Error
	 */
	private function register_post_type( array $data ): WP_Post_Type|WP_Error {
		$labels = array(
			'name'          => $data['plural_label'],
			'singular_name' => $data['singular_label'],
		);

		$args = array(
			'labels'       => $labels,
			'rewrite'      => array( 'slug' => $data['post_type_slug'] ),
			'public'       => true,
			'show_in_rest' => true,
		);

		return register_post_type( $data['post_type_key'], $args );
	}

	/**
	 * Stores the post type in the options table.
	 *
	 * @param array $data The data to use when storing the post type.
	 *
	 * @return array Return the registered post types.
	 * @since 0.1.0-alpha
	 */
	private function store_post_type( array $data ): array {
		$post_types                           = get_option( CUSTOM_PTT_POST_TYPE_OPTION_NAME, array() );
		$post_types[ $data['post_type_key'] ] = $data;

		update_option( CUSTOM_PTT_POST_TYPE_OPTION_NAME, $post_types );

		return get_option( CUSTOM_PTT_POST_TYPE_OPTION_NAME, array() );
	}

	/**
	 * Enqueues the plugin's styles on the specific admin page.
	 *
	 * @since 0.1.0-alpha
	 * @return void
	 */
	public function enqueue_styles(): void {
		$screen = get_current_screen();

		if ( isset( $screen->id ) && 'custom-ptt_page_add-custom-post-types-taxonomies-post-types' === $screen->id ) {
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
