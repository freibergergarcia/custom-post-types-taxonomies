<?php

use Custom_PTT\Admin\Taxonomy_List_Table;

?>
<div class="wrap">
	<h1>
		<?php
		echo esc_html__( 'Custom Taxonomies', 'custom-post-types-taxonomies' );
		?>
		<a href="
		<?php
		echo esc_url( admin_url( 'admin.php?page=add-custom-post-types-taxonomies-taxonomies' ) );
		?>
		"
			class="page-title-action">
			<?php
			echo esc_html__( 'Add New', 'custom-post-types-taxonomies' );
			?>
		</a>
	</h1>

	<?php
	$taxonomy_list_table = new Taxonomy_List_Table();
	$taxonomy_list_table->prepare_items();
	$taxonomy_list_table->display();
	?>
</div>
