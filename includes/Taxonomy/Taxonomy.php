<?php

declare( strict_types=1 );

namespace Custom_PTT\Taxonomy;

use Custom_PTT\Infrastructure\Registerable;
use Exception;
use WP_Error;

/**
 * Taxonomy class.
 *
 * This class is responsible for registering and managing a custom taxonomy.
 *
 * @package   Custom_PTT
 * @since     0.1.0-alpha
 * @see       https://developer.wordpress.org/plugins/taxonomies/
 */
class Taxonomy implements Registerable {

	/**
	 * Register the taxonomy.
	 *
	 * @return void
	 * @since 0.1.0-alpha
	 */
	public function register(): void {
		add_action( 'init', array( $this, 'register_taxonomy_on_init' ) );
	}

	/**
	 * Register the taxonomy.
	 *
	 * This method reads the custom taxonomies from the options table and registers them using the
	 * `register_taxonomy()` function. The taxonomies are registered with default arguments, unless
	 * custom arguments are specified. Developers can modify the arguments for each taxonomy
	 * using the `custom_ptt_taxonomy_args` filter hook.
	 *
	 * @return void
	 * @throws Exception
	 * @since 0.1.0-alpha
	 */
	public function register_taxonomy_on_init(): void {

		$taxonomies = get_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME, array() );
		if ( empty( $taxonomies ) ) {
			return;
		}

		foreach ( $taxonomies as $taxonomy_slug => $taxonomy_data ) {
			$labels = array(
				'name'          => $taxonomy_data['plural_label'],
				'singular_name' => $taxonomy_data['singular_label'],
			);

			$args = array(
				'labels'            => $labels,
				'public'            => true,
				'show_ui'           => true,
				'show_in_menu'      => true,
				'show_in_nav_menus' => true,
				'show_in_rest'      => true,
			);
			$args = wp_parse_args( $taxonomy_data, $args );

			/**
			 * Filters the arguments used when registering a taxonomy.
			 *
			 * @param array $args The arguments used when registering a taxonomy.
			 * @param string $taxonomy_slug The taxonomy slug.
			 * @param array $taxonomy_data The taxonomy data.
			 * @since 0.1.0-alpha
			 */
			$args = apply_filters( 'custom_ptt_taxonomy_args', $args, $taxonomy_slug, $taxonomy_data );

			$tax_result = register_taxonomy( $taxonomy_slug, $taxonomy_data['post_type'], $args );

			if ( $tax_result instanceof WP_Error ) {
				throw new Exception( $tax_result->get_error_message() );
			}
		}

		/**
		 * Fires after the taxonomies are registered.
		 *
		 * @param array $taxonomies The taxonomies that were registered.
		 * @since 0.1.0-alpha
		 */
		do_action( 'custom_ptt_registered_taxonomies', $taxonomies );
	}
}
