<div class="wrap">
	<h1><?php echo esc_html__( 'Add New Taxonomy', 'custom-post-types-taxonomies' ); ?></h1>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		
		<?php wp_nonce_field( 'custom_ptt_save_taxonomy', 'custom_ptt_taxonomy_nonce' ); ?>
		<input type="hidden" name="action" value="custom_ptt_save_taxonomy">
		
		<div class="form-field">
			<label for="taxonomy-slug"><?php esc_html_e( 'Taxonomy slug:', 'custom-post-types-taxonomies' ); ?></label>
			<input type="text" id="taxonomy-slug" name="taxonomy-slug" value="<?php echo esc_attr( $taxonomy_data['taxonomy_slug'] ?? '' ); ?>" required>
		</div>
		<div class="form-field">
			<label for="plural-label"><?php esc_html_e( 'Plural Label:', 'custom-post-types-taxonomies' ); ?></label>
			<input type="text" id="plural-label" name="plural-label" value="<?php echo esc_attr( $taxonomy_data['plural_label'] ?? '' ); ?>" required>
		</div>
		<div class="form-field">
			<label for="singular-label"><?php esc_html_e( 'Singular Label:', 'custom-post-types-taxonomies' ); ?></label>
			<input type="text" id="singular-label" name="singular-label" value="<?php echo esc_attr( $taxonomy_data['singular_label'] ?? '' ); ?>" required>
		</div>
		<div class="form-field">
			<label><?php esc_html_e( 'Attach to Post Types:', 'custom-post-types-taxonomies' ); ?></label>
			<div class="checkbox-group">
				<input type="checkbox" id="post-type-post" name="post-type[]" value="post" <?php checked( in_array( 'post', $taxonomy_data['post_type'] ?? array(), true ) ); ?>>
				<label for="post-type-post"><?php esc_html_e( 'Posts', 'custom-post-types-taxonomies' ); ?></label>
			</div>
			<div class="checkbox-group">
				<input type="checkbox" id="post-type-page" name="post-type[]" value="page" <?php checked( in_array( 'page', $taxonomy_data['post_type'] ?? array(), true ) ); ?>>
				<label for="post-type-page"><?php esc_html_e( 'Pages', 'custom-post-types-taxonomies' ); ?></label>
			</div>
			<div class="checkbox-group">
				<input type="checkbox" id="post-type-media" name="post-type[]" value="attachment" <?php checked( in_array( 'attachment', $taxonomy_data['post_type'] ?? array(), true ) ); ?>>
				<label for="post-type-media"><?php esc_html_e( 'Media', 'custom-post-types-taxonomies' ); ?></label>
			</div>
		</div>
		<?php submit_button(); ?>
	</form>
</div>