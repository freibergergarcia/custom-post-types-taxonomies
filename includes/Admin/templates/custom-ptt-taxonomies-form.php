<?php
/**
 * Custom Post Types Taxonomies Admin Template
 *
 * @var $taxonomy_data
 */
?>
<div class="min-h-screen bg-gray-100">

	<?php require CUSTOM_PTT_DIR . 'includes/Admin/templates/custom-ptt-header.php'; ?>

	<div class="wrap max-w-lg mx-auto">
		<?php
		if (
			isset( $_REQUEST['custom_ptt_taxonomy_nonce'] ) &&
			wp_verify_nonce( $_REQUEST['custom_ptt_taxonomy_nonce'], 'custom_ptt_save_taxonomy' ) &&
			is_null( $taxonomy_data ) &&
			isset( $_GET['action'] ) &&
			'edit' === sanitize_text_field( $_GET['action'] )
		) {
			?>

			<div class="flex flex-col justify-center items-center h-full mt-10 w-4/5">
				<div class="bg-red-100 border-l-4 border-red-500 text-left px-4 py-3 rounded-lg w-full max-w-md">
					<h2 class="text-xl font-bold">Oops!</h2>
					<p class="mt-2 text-md text-gray-600">Invalid taxonomy.</p>
					<a href="#" onclick="history.back();" class="text-blue-600 hover:text-blue-800 visited:text-purple-600">Go back</a>
				</div>

			</div>

		<?php } else { ?>

			<div class="mt-10 w-full">
				<h1 class="text-2xl font-bold mb-6">
					<?php echo ! empty( $taxonomy_data ) ? esc_html__( 'Edit Taxonomy', 'custom-post-types-taxonomies' ) : esc_html__( 'Add New Taxonomy', 'custom-post-types-taxonomies' ); ?>
				</h1>

				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 max-w-2xl mx-auto">
					<?php wp_nonce_field( 'custom_ptt_save_taxonomy', 'custom_ptt_taxonomy_nonce' ); ?>
					<input type="hidden" name="action" value="custom_ptt_save_taxonomy">

					<div class="mb-4">
						<label for="taxonomy-slug" class="block text-gray-700 text-sm font-bold mb-2"><?php esc_html_e( 'Taxonomy slug:', 'custom-post-types-taxonomies' ); ?></label>
						<input type="text" id="taxonomy-slug" name="taxonomy-slug" value="<?php echo esc_attr( $taxonomy_data['taxonomy_slug'] ?? '' ); ?>" class="form-input w-full" required>
					</div>

					<!-- Plural Label -->
					<div class="mb-4">
						<label for="plural-label" class="block text-gray-700 text-sm font-bold mb-2"><?php esc_html_e( 'Plural Label:', 'custom-post-types-taxonomies' ); ?></label>
						<input type="text" id="plural-label" name="plural-label" value="<?php echo esc_attr( $taxonomy_data['plural_label'] ?? '' ); ?>" class="form-input w-full">
					</div>

					<!-- Singular Label -->
					<div class="mb-4">
						<label for="singular-label" class="block text-gray-700 text-sm font-bold mb-2"><?php esc_html_e( 'Singular Label:', 'custom-post-types-taxonomies' ); ?></label>
						<input type="text" id="singular-label" name="singular-label" value="<?php echo esc_attr( $taxonomy_data['singular_label'] ?? '' ); ?>" class="form-input w-full" required>
					</div>

					<!-- Attach to Post Types -->
					<div class="mb-6">
						<label class="block text-gray-700 text-sm font-bold mb-2"><?php esc_html_e( 'Attach to Post Types:', 'custom-post-types-taxonomies' ); ?></label>
						<div class="flex flex-col pl-4">
							<?php
							$post_types = get_post_types( array( 'public' => true ), 'objects' ); // Retrieve post types as objects to access labels

							foreach ( $post_types as $post_type ) {
								if ( in_array( $post_type->name, array( 'attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'oembed_cache', 'user_request', 'wp_block' ), true ) ) {
									continue;
								}

								$is_checked = in_array( $post_type->name, $taxonomy_data['post_type'] ?? array(), true );
								?>
							<div class="mb-2">
								<input type="checkbox" id="post-type-<?php echo esc_attr( $post_type->name ); ?>" name="post-type[]" value="<?php echo esc_attr( $post_type->name ); ?>" <?php checked( $is_checked ); ?> class="form-checkbox">
								<label for="post-type-<?php echo esc_attr( $post_type->name ); ?>" class="text-sm"><?php echo esc_html( $post_type->labels->singular_name ); ?></label>
							</div>
								<?php
							}
							?>
						</div>
					</div>

					<!-- Submit Button -->
					<?php if ( isset( $taxonomy_data ) ) { ?>
						<?php submit_button( esc_attr__( 'Update Taxonomy', 'custom-post-types-taxonomies' ), 'primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline', 'submit', false ); ?>
					<?php } else { ?>
						<?php submit_button( esc_attr__( 'Add Taxonomy', 'custom-post-types-taxonomies' ), 'primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline', 'submit', false ); ?>
					<?php } ?>

				</form>
			</div>
		<?php } ?>
	</div>
	<?php require CUSTOM_PTT_DIR . 'includes/Admin/templates/custom-ptt-footer.php'; ?>
</div>