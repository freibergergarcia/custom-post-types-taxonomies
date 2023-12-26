<?php

declare(strict_types=1);

namespace Custom_PTT\Admin;

use Exception;
use WP_List_Table;

/**
 * Taxonomy_List_Table Class
 *
 * This class is responsible for rendering the list of registered taxonomies in
 * a tabular format within the WordPress admin interface. It extends the WP_List_Table
 * class to leverage WordPress's built-in table rendering functionality.
 *
 * @package Custom_PTT\Taxonomy
 * @since 0.1.0-alpha
 */
class Taxonomy_List_Table extends WP_List_Table {

	/**
	 * Constructor method.
	 *
	 * @since 0.1.0-alpha
	 * @throws Exception
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => __( 'Taxonomy', 'custom-post-types-taxonomies' ),
				'plural'   => __( 'Taxonomies', 'custom-post-types-taxonomies' ),
				'ajax'     => false,
			)
		);
	}

	/**
	 * Get a list of columns.
	 *
	 * @since 0.1.0-alpha
	 * @return array
	 * @throws Exception
	 */
	public function get_columns(): array {
		return array(
			'name'         => __( 'Name', 'custom-post-types-taxonomies' ),
			'label'        => __( 'Label', 'custom-post-types-taxonomies' ),
			'slug'         => __( 'Slug', 'custom-post-types-taxonomies' ),
			'public'       => __( 'Public', 'custom-post-types-taxonomies' ),
			'hierarchical' => __( 'Hierarchical', 'custom-post-types-taxonomies' ),
		);
	}

	/**
	 * Get a list of sortable columns.
	 *
	 * @since 0.1.0-alpha
	 * @return array
	 */
	public function get_sortable_columns(): array {
		return array(
			'name'  => array( 'name', true ),
			'label' => array( 'label', false ),
		);
	}

	/**
	 * Prepare the items for the table to process.
	 *
	 * @since 0.1.0-alpha
	 * @return void
	 * @throws Exception
	 */
	public function prepare_items(): void {
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$orderby = 'name';
		$order   = 'asc';
		if (
			isset( $_REQUEST['custom_ptt_taxonomy_nonce'] ) &&
			wp_verify_nonce( $_REQUEST['custom_ptt_taxonomy_nonce'], 'custom_ptt_save_taxonomy' )
		) {
			$orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'name';
			$order   = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'asc';
		}

		$taxonomies = get_taxonomies( array( '_builtin' => false ), 'objects' );

		if ( empty( $taxonomies ) ) {
			return;
		}

		/**
		 * Filter the taxonomies to display in the list table, removing any taxonomies that are not
		 * registered by the plugin.
		 */
		$plugin_taxonomies = get_option( CUSTOM_PTT_TAXONOMY_OPTION_NAME, array() );
		foreach ( $taxonomies as $taxonomy_slug => $taxonomy ) {
			if ( ! in_array( $taxonomy_slug, array_keys( $plugin_taxonomies ), true ) ) {
				unset( $taxonomies[ $taxonomy_slug ] );
			}
		}

		usort(
			$taxonomies,
			function ( $a, $b ) use ( $orderby, $order ) {
				if ( ! isset( $a->$orderby ) || ! isset( $b->$orderby ) ) {
					return 0;
				}

				if ( $a->$orderby === $b->$orderby ) {
					return 0;
				}

				if ( 'asc' === $order ) {
					return ( $a->$orderby < $b->$orderby ) ? -1 : 1;
				}

				return ( $a->$orderby < $b->$orderby ) ? 1 : -1;
			}
		);

		$this->items = $taxonomies;
	}

	/**
	 * Render the Name column with an Edit action.
	 *
	 * @param object $item The current item.
	 *
	 * @since 0.1.0-alpha
	 * @return string The Name column HTML.
	 * @throws Exception
	 */
	public function column_name( object $item ): string {
		$edit_query_args = array(
			'page'     => wp_unslash( 'add-custom-post-types-taxonomies-taxonomies' ),
			'action'   => 'edit',
			'taxonomy' => $item->name,
		);

		$edit_url = esc_url( add_query_arg( $edit_query_args, admin_url( 'admin.php' ) ) );

		return sprintf(
			'<strong><a class="row-title" href="%2$s" aria-label="%1$s (Edit)">%1$s</a></strong><div class="row-actions"><span class="edit"><a href="%2$s" title="%3$s">%3$s</a></span></div>',
			esc_html( $item->label ), // Display the taxonomy label instead of the name
			$edit_url,
			__( 'Edit', 'custom-post-types-taxonomies' )
		);
	}

	/**
	 * Default column rendering method.
	 *
	 * @param object $item The current item.
	 * @param string $column_name The name of the column.
	 *
	 * @since 0.1.0-alpha
	 * @return string
	 * @throws Exception
	 */
	public function column_default( $item, $column_name ): string {
		return match ( $column_name ) {
			'label' => $item->label,
			'slug' => $item->rewrite['slug'] ?? '',
			'public' => $item->public ? __( 'Yes', 'custom-post-types-taxonomies' ) : __( 'No', 'custom-post-types-taxonomies' ),
			'hierarchical' => $item->hierarchical ? __( 'Yes', 'custom-post-types-taxonomies' ) : __( 'No', 'custom-post-types-taxonomies' ),
			default => '',
		};
	}
}
