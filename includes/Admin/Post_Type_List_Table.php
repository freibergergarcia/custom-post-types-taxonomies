<?php

declare(strict_types=1);

namespace Custom_PTT\Admin;

use Exception;
use WP_List_Table;

/**
* Post_Type_List_Table Class
 *
 * This class is responsible for rendering the list of registered taxonomies in
 * a tabular format within the WordPress admin interface. It extends the WP_List_Table
 * class to leverage WordPress's built-in table rendering functionality.
 *
 * @package Custom_PTT\Taxonomy
 * @since 0.1.0-alpha
 */
class Post_Type_List_Table extends WP_List_Table {

	/**
	 * Constructor
	 *
	 * @throws Exception
	 *
	 * @since 0.1.0-alpha
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => __( 'Post Type', 'custom-post-types-taxonomies' ),
				'plural'   => __( 'Post Types', 'custom-post-types-taxonomies' ),
				'ajax'     => false,
			)
		);
	}

	/**
	 * Implements get_columns method.
	 *
	 * @return array
	 * @throws Exception
	 *
	 * @since 0.1.0-alpha
	 */
	public function get_columns(): array {
		return array(
			'name'         => __( 'Name', 'custom-post-types-taxonomies' ),
			'label'        => __( 'Label', 'custom-post-types-taxonomies' ),
			'public'       => __( 'Public', 'custom-post-types-taxonomies' ),
			'hierarchical' => __( 'Hierarchical', 'custom-post-types-taxonomies' ),
		);
	}

	/**
	 * Implements get_sortable_columns method.
	 *
	 * @return array[]
	 *
	 * @since 0.1.0-alpha
	 */
	public function get_sortable_columns(): array {
		return array(
			'name'  => array( 'name', true ),
			'label' => array( 'label', false ),
		);
	}

	/**
	 * Implements prepare_items method.
	 *
	 * @return void
	 * @throws Exception
	 *
	 * @since 0.1.0-alpha
	 */
	public function prepare_items(): void {
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		// @todo implement ordering

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
	 * @return string The Name column HTML.
	 * @throws Exception
	 *
	 * @since 0.1.0-alpha
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
