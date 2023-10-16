<?php

declare( strict_types=1 );

namespace WordPress_Related\Taxonomy;

use Exception;
use WordPress_Related\Config\Config_Loader;
use WordPress_Related\Config\Config_Loader_Exception;
use WordPress_Related\Infrastructure\Registerable;
use WordPress_Related\Utilities;

/**
 * Taxonomy_Form_Page Class
 *
 * This class is responsible for rendering and handling the taxonomy creation form.
 *
 * @package WordPress_Related\Taxonomy
 * @since 1.0.0
 * @version 1.0.0
 */
class Taxonomy_Form_Page implements Registerable {

	use Utilities;

	private Config_Loader $config_loader;

	/**
	 * Constructor injection of the config loader
	 *
	 * @param Config_Loader $config_loader The config loader instance.
	 *
	 * @throws Exception If any other error occurs.
	 */
	public function __construct( Config_Loader $config_loader ) {
		$this->config_loader = $config_loader;
	}

	/**
	 * Register the service with WordPress.
	 *
	 * Hooks the render_form method to the appropriate action.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_menu', array( $this, 'add_form_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Register settings dynamically based on configuration.
	 *
	 * This method reads fields configuration from the Config_Loader,
	 * then iterates through the configuration to register each setting field.
	 * It also registers a settings section and the settings themselves.
	 *
	 * @throws Config_Loader_Exception If there's an issue loading the configuration.
	 * @throws Exception If any other error occurs.
	 *
	 * @return void
	 */
	public function register_settings(): void {

		$config = $this->config_loader->load();

		register_setting(
			'wordpress_related_taxonomy_settings_group',
			'wordpress_related_taxonomy_settings',
			array( $this, 'validate_settings' )
		);

		add_settings_section(
			'wordpress_related_taxonomy_settings_section',
			__( 'Taxonomy Settings', 'wordpress-related' ),
			null,
			'wordpress_related_taxonomy_settings_page'
		);

		foreach ( $config as $key => $field ) {

			/**
			 * Let's skip args for now as this is a huge field list we need to
			 *  work further on.
			 */
			if ( 'args' === $key ) {
				continue;
			}

			do_action( 'qm/debug', $key );

			$title = $this->format_snake_case_to_title_case( $key );

			// Dynamically add settings field based on config
			add_settings_field(
				'wordpress-related-' . $key,
				__( $title, 'wordpress-related' ),
				array( $this, 'render_field' ),
				'wordpress_related_taxonomy_settings_page',
				'wordpress_related_taxonomy_settings_section',
				array(
					'key'   => $key,
					'value' => $field,
				)
			);
		}
	}

	/**
	 * Add the form page to the WordPress admin menu.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function add_form_page(): void {
		add_submenu_page(
			'wordpress-related',  // Corrected parent slug
			__( 'Add New Taxonomy', 'wordpress-related' ),
			__( 'Add New', 'wordpress-related' ),
			'manage_options',
			'add-wordpress-related-taxonomies',
			array( $this, 'render_form' )
		);
	}

	/**
	 * Render the taxonomy creation form.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function render_form(): void {
		?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Add New Taxonomy', 'wordpress-related' ); ?></h1>
            <form method="post" action="options.php">
				<?php
				settings_fields( 'wordpress_related_taxonomy_settings_group' );
				do_settings_sections( 'wordpress_related_taxonomy_settings_page' );  // Updated this line
				submit_button();
				?>
            </form>
        </div>
		<?php
	}

	/**
	 * Render a settings field.
	 *
	 * This method renders a single settings field based on provided arguments.
	 * It supports different input types and optionally renders a description below the field.
	 *
	 * @param array $args The arguments for the field.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function render_field( array $args ): void {

		do_action( 'qm/debug', $args );

		// Retrieve the option values from the database
		$options = get_option( 'wordpress_related_taxonomy_settings' );

		// Extract the key and value from the args array
		$key   = $args['key'];
		$field = $args['value'];

		// Use the key to get the current value of this field from the options
		$value = $options[ $key ] ?? '';

		// Assuming each field in the config has a 'type' property
		$type = $field['type'] ?? 'text';  // Default to text if type is not set

		// Render the input field
		echo "<input type='" . esc_attr( $type ) . "' name='wordpress_related_taxonomy_settings[" . esc_attr( $key ) . "]' value='" . esc_attr( $value ) . "'>";

		// If there's a description, render it below the field
		if ( ! empty( $field['description'] ) ) {
			echo "<p class='description'>" . esc_html( $field['description'] ) . '</p>';
		}
	}


	public function validate_settings( array $input ): array {
		do_action( 'qm/debug', 'validate_settings called' );

		// Validation code here
		return $input;
	}
}
