<?php

namespace WordPress_Related\Taxonomy;

use Exception;
use WP_List_Table;

/**
 * Taxonomy_List_Table Class
 *
 * This class is responsible for rendering the list of registered taxonomies in
 * a tabular format within the WordPress admin interface. It extends the WP_List_Table
 * class to leverage WordPress's built-in table rendering functionality.
 *
 * @package WordPress_Related\Taxonomy
 * @since 1.0.0
 * @version 1.0.0
 */
class Taxonomy_List_Table extends WP_List_Table {

	/**
	 * Constructor method.
	 *
	 * @throws Exception
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => __( 'Taxonomy', 'wordpress-related' ),
				'plural'   => __( 'Taxonomies', 'wordpress-related' ),
				'ajax'     => false,
			)
		);
	}

	/**
	 * Get a list of columns.
	 *
	 * @return array
	 * @throws Exception
	 */
	public function get_columns(): array {
		return array(
			'name'         => __( 'Name', 'wordpress-related' ),
			'label'        => __( 'Label', 'wordpress-related' ),
			'public'       => __( 'Public', 'wordpress-related' ),
			'hierarchical' => __( 'Hierarchical', 'wordpress-related' ),
		);
	}

	/**
	 * Get a list of sortable columns.
	 *
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
	 * @return void
	 * @throws Exception
	 */
	public function prepare_items(): void {
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		// Fetch only custom taxonomies by setting '_builtin' to false
		$taxonomies  = get_taxonomies( array( '_builtin' => false ), 'objects' );
		$this->items = $taxonomies;
	}

	/**
	 * Render the Name column with an Edit action.
	 *
	 * @param object $item The current item.
	 *
	 * @return string The Name column HTML.
	 * @throws Exception
	 */
	public function column_name( object $item ): string {
		$edit_query_args = array(
			'page'     => wp_unslash( $_REQUEST['page'] ),
			'action'   => 'edit',
			'taxonomy' => $item->name,
		);

		$edit_url = esc_url( add_query_arg( $edit_query_args, admin_url( 'admin.php' ) ) );

		return sprintf(
			'<strong><a class="row-title" href="%2$s" aria-label="%1$s (Edit)">%1$s</a></strong><div class="row-actions"><span class="edit"><a href="%2$s" title="%3$s">%3$s</a></span></div>',
			esc_html( $item->label ), // Display the taxonomy label instead of the name
			$edit_url,
			__( 'Edit', 'wordpress-related' )
		);
	}

	/**
	 * Default column rendering method.
	 *
	 * @param object $item The current item.
	 * @param string $column_name The name of the column.
	 *
	 * @return string
	 * @throws Exception
	 */
	public function column_default( $item, $column_name ): string {
		return match ( $column_name ) {
			'label' => $item->label,
			'public' => $item->public ? __( 'Yes', 'wordpress-related' ) : __( 'No', 'wordpress-related' ),
			'hierarchical' => $item->hierarchical ? __( 'Yes', 'wordpress-related' ) : __( 'No', 'wordpress-related' ),
			default => '',
		};
	}
}
