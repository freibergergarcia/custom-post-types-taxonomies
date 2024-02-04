<?php

namespace Custom_PTT\Admin;

use Exception;
use WP_List_Table;

class Post_Type_List_Table extends WP_List_Table {

	public function __construct() {
		parent::__construct(
			array(
				'singular' => __( 'Post Type', 'custom-post-types-taxonomies' ),
				'plural'   => __( 'Post Types', 'custom-post-types-taxonomies' ),
				'ajax'     => false,
			)
		);
	}

	public function get_columns(): array {
		return array(
			'name'         => __( 'Name', 'custom-post-types-taxonomies' ),
			'label'        => __( 'Label', 'custom-post-types-taxonomies' ),
			'public'       => __( 'Public', 'custom-post-types-taxonomies' ),
			'hierarchical' => __( 'Hierarchical', 'custom-post-types-taxonomies' ),
		);
	}

	public function get_sortable_columns(): array {
		return array(
			'name'  => array( 'name', true ),
			'label' => array( 'label', false ),
		);
	}

	public function prepare_items(): void {
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		// Example of sorting. Implement your logic here.
		$orderby = $_REQUEST['orderby'] ?? 'name';
		$order   = $_REQUEST['order'] ?? 'asc';

		$post_types = get_post_types( array( '_builtin' => false ), 'object' );

		if ( empty( $post_types ) ) {
			return;
		}

		/**
		 * Filter the post types to display in the list table, removing any post types that are not
		 * registered by the plugin.
		 */
		$plugin_post_types = get_option( CUSTOM_PTT_POST_TYPE_OPTION_NAME, array() );
		foreach ( $post_types as $post_type_key => $post_type ) {
			if ( ! in_array( $post_type_key, array_keys( $plugin_post_types ), true ) ) {
				unset( $post_types[ $post_type_key ] );
			}
		}

		$this->items = $post_types;
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
			'page'             => wp_unslash( 'add-custom-post-types-taxonomies-post-types' ),
			'action'           => 'edit',
			'custom_post_type' => $item->name,
		);

		$edit_url = esc_url( add_query_arg( $edit_query_args, admin_url( 'admin.php' ) ) );

		return sprintf(
			'<strong><a class="row-title" href="%2$s" aria-label="%1$s (Edit)">%1$s</a></strong><div class="row-actions"><span class="edit"><a href="%2$s" title="%3$s">%3$s</a></span></div>',
			esc_html( $item->label ),
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
