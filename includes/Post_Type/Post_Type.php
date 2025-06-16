<?php

declare( strict_types=1 );

namespace Custom_PTT\Post_Type;

use Custom_PTT\Infrastructure\Registerable;
use Exception;
use WP_Error;

/**
 * Post_Type class.
 *
 * This class is responsible for registering and managing custom post types.
 *
 * @package   Custom_PTT
 * @since     0.1.0-alpha
 * @see       https://developer.wordpress.org/plugins/taxonomies/
 */
class Post_Type implements Registerable {

	/**
	 * Cache group for post type caching.
	 *
	 * @var string
	 * @since 0.2.1
	 */
	const CACHE_GROUP = 'custom_ptt_post_types';

	/**
	 * Register the post type.
	 *
	 * @return void
	 * @since 0.1.0-alpha
	 */
	public function register(): void {
		add_action( 'init', array( $this, 'register_post_type_on_init' ) );
	}

	/**
	 * Handles the actual registration of post types on init.
	 *
	 * This method reads the custom post types from the options table and registers them using the
	 * `register_post_type()` function. The post types are registered with default arguments, unless
	 * custom arguments are specified. Developers can modify the arguments for each post type
	 * using the `custom_ptt_post_type_args` filter hook.
	 *
	 * @return void
	 * @since 0.1.0-alpha
	 */
	public function register_post_type_on_init(): void {
		$post_types = get_option( CUSTOM_PTT_POST_TYPE_OPTION_NAME, array() );
		if ( empty( $post_types ) ) {
			return;
		}

		$successfully_registered = array();

		foreach ( $post_types as $post_type_key => $post_type_data ) {
			try {
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
					'show_ui'           => true,
					'show_in_menu'      => true,
				);
				$args = wp_parse_args( $post_type_data, $args );

				/**
				 * Filters the arguments used when registering a post type.
				 *
				 * @param array $args The arguments used when registering a post type.
				 * @param string $post_type_key The post type slug.
				 * @param array $post_type_data The post type data.
				 *
				 * @since 0.1.0-alpha
				 */
				$args = apply_filters( 'custom_ptt_post_type_args', $args, $post_type_key, $post_type_data );

				$post_type_result = register_post_type( $post_type_key, $args );

				if ( $post_type_result instanceof WP_Error ) {
					throw new Exception( $post_type_result->get_error_message() );
				}

				// Only add to cache if registration was successful
				$successfully_registered[ $post_type_key ] = $post_type_data;

			} catch ( Exception $e ) {
				// Log error in debug mode but continue processing other post types
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
					error_log( sprintf( 'Custom PTT Plugin - Error registering post type %s: %s', $post_type_key, $e->getMessage() ) );
				}
				// Continue to next post type instead of breaking the entire process
				continue;
			}
		}

		// Cache only successfully registered post types for performance
		wp_cache_set( 'registered_post_types', $successfully_registered, self::CACHE_GROUP, HOUR_IN_SECONDS );

		/**
		 * Fires after the post types are registered.
		 *
		 * @param array $post_types The post types that were registered.
		 *
		 * @since 0.1.0-alpha
		 */
		do_action( 'custom_ptt_registered_post_types', $successfully_registered );
	}

	/**
	 * Register a single post type.
	 *
	 * @param string $post_type_key The post type slug.
	 * @param array  $post_type_data The post type data.
	 * @throws Exception If post type registration fails.
	 * @return void
	 */
	private function register_single_post_type( string $post_type_key, array $post_type_data ): void {
		$args = $this->get_post_type_args( $post_type_key, $post_type_data );
		
		$post_type_result = register_post_type( $post_type_key, $args );

		if ( $post_type_result instanceof WP_Error ) {
			throw new Exception( esc_html( $post_type_result->get_error_message() ) );
		}
	}

	/**
	 * Validate cache integrity by comparing cached data with option data.
	 *
	 * @return bool True if cache is valid, false if corrupted or missing.
	 * @since 0.2.1
	 */
	public function validate_cache_integrity(): bool {
		$cached_data = wp_cache_get( 'registered_post_types', self::CACHE_GROUP );
		$option_data = get_option( CUSTOM_PTT_POST_TYPE_OPTION_NAME, array() );
		
		// If no cache exists, consider it invalid
		if ( false === $cached_data ) {
			return false;
		}
		
		// Compare cached data with option data
		return $cached_data === $option_data;
	}

	/**
	 * Get cached or fresh post type arguments.
	 *
	 * @param string $post_type_key The post type slug.
	 * @param array  $post_type_data The post type data.
	 * @return array
	 */
	private function get_post_type_args( string $post_type_key, array $post_type_data ): array {
		$labels = array(
			'name'          => $post_type_data['plural_label'],
			'singular_name' => $post_type_data['singular_label'],
		);

		$default_args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_rest'      => true,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
		);
		
		$args = wp_parse_args( $post_type_data, $default_args );

		/**
		 * Filters the arguments used when registering a post type.
		 *
		 * @param array  $args           The arguments used when registering a post type.
		 * @param string $post_type_key  The post type slug.
		 * @param array  $post_type_data The post type data.
		 * @since 0.1.0-alpha
		 */
		$args = apply_filters( 'custom_ptt_post_type_args', $args, $post_type_key, $post_type_data );
		
		$args_hash   = md5( wp_json_encode( $args ) );
		$cache_key   = "post_type_{$post_type_key}_{$args_hash}";
		$cached_args = wp_cache_get( $cache_key, self::CACHE_GROUP );
		
		if ( false === $cached_args ) {
			wp_cache_set( $cache_key, $args, self::CACHE_GROUP, HOUR_IN_SECONDS );
			return $args;
		}

		return $cached_args;
	}
}
