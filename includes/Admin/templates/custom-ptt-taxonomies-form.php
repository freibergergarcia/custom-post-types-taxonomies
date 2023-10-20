	<div class="wrap">
		<h1>
		<?php
			echo esc_html__( 'Add New Taxonomy', 'custom-post-types-taxonomies' );
		?></h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'Custom_PTT_taxonomy_settings_group' );
			do_settings_sections( 'Custom_PTT_taxonomy_settings_page' );  // Updated this line
			submit_button();
			?>
		</form>
	</div>
<?php