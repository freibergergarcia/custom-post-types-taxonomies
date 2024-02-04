<?php

use Custom_PTT\Admin\Post_Type_List_Table;

?>
<div class="wrap">
	<h1>
		<?php
		echo esc_html__( 'Custom Post Types', 'custom-post-types-taxonomies' );
		?>
		<a href="
		<?php
		echo esc_url( admin_url( 'admin.php?page=add-custom-post-types-taxonomies-post-types' ) );
		?>
		"
			class="page-title-action">
			<?php
			echo esc_html__( 'Add New', 'custom-post-types-taxonomies' );
			?>
		</a>
	</h1>

	<?php
	$post_type_list_table = new Post_Type_List_Table();
	$post_type_list_table->prepare_items();
	$post_type_list_table->display();
	?>
</div>
