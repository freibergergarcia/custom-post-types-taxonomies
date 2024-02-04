<?php
/**
 * Custom Post Types Admin Template
 *
 * @var $post_type_data
 * @var $taxonomies
 */
?>
<div class="min-h-screen bg-gray-100">

	<?php require CUSTOM_PTT_DIR . 'includes/Admin/templates/custom-ptt-header.php'; ?>

	<div class="wrap max-w-lg mx-auto">
		<?php
		if (
			isset( $_REQUEST['custom_ptt_post_type_nonce'] ) &&
			wp_verify_nonce( $_REQUEST['custom_ptt_post_type_nonce'], 'custom_ptt_save_post_type' ) &&
			is_null( $post_type_data ) &&
			isset( $_GET['action'] ) &&
			'edit' === sanitize_text_field( $_GET['action'] )
		) {
			?>

			<div class="flex flex-col justify-center items-center h-full mt-10 w-4/5">
				<div class="bg-red-100 border-l-4 border-red-500 text-left px-4 py-3 rounded-lg w-full max-w-md">
					<h2 class="text-xl font-bold">Oops!</h2>
					<p class="mt-2 text-md text-gray-600">Invalid post_type.</p>
					<a href="#" onclick="history.back();" class="text-blue-600 hover:text-blue-800 visited:text-purple-600">Go back</a>
				</div>

			</div>

		<?php } else { ?>

			<div class="mt-10 w-full">
				<h1 class="text-2xl font-bold mb-6">
					<?php echo ! empty( $post_type_data ) ? esc_html__( 'Edit Post Type', 'custom-post-types-post-types' ) : esc_html__( 'Add New Post Type', 'custom-post-types-post-types' ); ?>
				</h1>

				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 max-w-2xl mx-auto">
					<?php wp_nonce_field( 'custom_ptt_save_post_type', 'custom_ptt_post_type_nonce' ); ?>
					<input type="hidden" name="action" value="custom_ptt_save_post_type">

					<div class="mb-4">
						<label for="post-type-slug" class="block text-gray-700 text-sm font-bold mb-2"><?php esc_html_e( 'Post Type slug:', 'custom-post-types-post-types' ); ?></label>
						<input type="text" id="post-type-slug" name="post-type-slug" value="<?php echo esc_attr( $post_type_data['post_type_slug'] ?? '' ); ?>" class="form-input w-full" required>
					</div>

					<div class="mb-4">
						<label for="plural-label" class="block text-gray-700 text-sm font-bold mb-2"><?php esc_html_e( 'Plural Label:', 'custom-post-types-post-types' ); ?></label>
						<input type="text" id="plural-label" name="plural-label" value="<?php echo esc_attr( $post_type_data['plural_label'] ?? '' ); ?>" class="form-input w-full">
					</div>

					<div class="mb-4">
						<label for="singular-label" class="block text-gray-700 text-sm font-bold mb-2"><?php esc_html_e( 'Singular Label:', 'custom-post-types-post-types' ); ?></label>
						<input type="text" id="singular-label" name="singular-label" value="<?php echo esc_attr( $post_type_data['singular_label'] ?? '' ); ?>" class="form-input w-full" required>
					</div>

					<div class="mb-4">
						<label for="post-type-key" class="block text-gray-700 text-sm font-bold mb-2"><?php esc_html_e( 'Post Type Key:', 'custom-post-types-post-types' ); ?></label>
						<input type="text" id="post-type-key" name="post-type-key" 
						<?php
						if ( ! empty( $post_type_data['post_type_key'] ) ) {
							echo 'disabled'; }
						?>
						value="<?php echo esc_attr( $post_type_data['post_type_key'] ?? '' ); ?>" class="form-input w-full" required>
						<input type="hidden" name="post-type-key" value="<?php echo esc_attr( $post_type_data['post_type_key'] ?? '' ); ?>">
					</div>

					<div class="mb-6">
						<label class="block text-gray-700 text-sm font-bold mb-2"><?php esc_html_e( 'Attach to Taxonomies:', 'custom-post-types-post-types' ); ?></label>
						<div class="flex flex-col pl-4">
							<?php foreach ( $taxonomies as $taxonomy_slug => $taxonomy_label ) { ?>
								<div class="mb-2">
									<input type="checkbox" id="taxonomy-<?php echo esc_attr( $taxonomy_slug ); ?>" name="taxonomies[]" value="<?php echo esc_attr( $taxonomy_slug ); ?>" <?php checked( in_array( $taxonomy_slug, $post_type_data['taxonomies'] ?? array(), true ) ); ?> class="form-checkbox">
									<label for="taxonomy-<?php echo esc_attr( $taxonomy_slug ); ?>" class="text-sm"><?php echo esc_html( $taxonomy_label ); ?></label>
								</div>
							<?php } ?>
						</div>
					</div>

					<?php if ( isset( $post_type_data ) ) { ?>
						<?php submit_button( esc_attr__( 'Update Post Type', 'custom-post-types-post-types' ), 'primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline', 'submit', false ); ?>
					<?php } else { ?>
						<?php submit_button( esc_attr__( 'Add Post Type', 'custom-post-types-post-types' ), 'primary bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline', 'submit', false ); ?>
					<?php } ?>

				</form>
			</div>
		<?php } ?>
	</div>
	<?php require CUSTOM_PTT_DIR . 'includes/Admin/templates/custom-ptt-footer.php'; ?>
</div>