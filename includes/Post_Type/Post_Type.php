<?php

namespace Custom_PTT\Post_Type;

use Custom_PTT\Infrastructure\Registerable;
use Exception;
use WP_Error;

class Post_Type implements Registerable {

	/**
	 * Register the post type.
	 *
	 * This method reads the custom post types from the options table and registers them using the
	 * `register_post_type()` function. The post types are registered with default arguments, unless
	 * custom arguments are specified in the options table. Developers can modify the arguments for
	 * each post type using the `custom_ptt_post_type_args` filter hook.
	 *
	 * @return void
	 * @throws Exception
	 * @since 0.1.0-alpha
	 */
	public function register(): void {
		add_action( 'init', array( $this, 'register_post_type_on_init' ) );
	}

	/**
	 * Handles the actual registration of post types on init.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function register_post_type_on_init(): void {
		$post_types = get_option( CUSTOM_PTT_POST_TYPE_OPTION_NAME, array() );
		if ( empty( $post_types ) ) {
			return;
		}

		//var_dump($post_types);

		foreach ( $post_types as $post_type_key => $post_type_data ) {
			$labels = array(
				'name'          => $post_type_data['plural_label'],
				'singular_name' => $post_type_data['singular_label'],
			);

			$args = array(
				'labels'            => $labels,
				'public'            => true,
				'show_in_rest'      => true,
				'show_in_admin_bar' => true,
				'show_in_nav_menus' => true,
			);
			$args = wp_parse_args( $post_type_data, $args );

			/**
			 * Filters the arguments used when registering a post type.
			 *
			 * @param array $args The arguments used when registering a post type.
			 * @param string $post_type_key The post type slug.
			 * @param array $post_type_data The post type data.
			 * @since 0.1.0-alpha
			 */
			$args = apply_filters( 'custom_ptt_post_type_args', $args, $post_type_key, $post_type_data );
			//
			//          echo "<pre>";
			//          print_r($args);
			//          print_r($post_type_key);

			$post_type_result = register_post_type( $post_type_key, $args );

			if ( $post_type_result instanceof WP_Error ) {
				throw new Exception( $post_type_result->get_error_message() );
			}
		}

		/**
		 * Fires after the post types are registered.
		 *
		 * @param array $post_types The post types that were registered.
		 * @since 0.1.0-alpha
		 */
		do_action( 'custom_ptt_registered_post_types', $post_types );
	}
}
