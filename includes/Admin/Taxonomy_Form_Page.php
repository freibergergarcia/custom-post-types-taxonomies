<?php

declare(strict_types=1);

namespace Custom_PTT\Admin;

use Exception;
use Custom_PTT\Config\Config_Loader;
use Custom_PTT\Config\Config_Loader_Exception;
use Custom_PTT\Infrastructure\Registerable;
use Custom_PTT\Utilities;

/**
 * Taxonomy_Form_Page Class
 *
 * This class is responsible for rendering and handling the taxonomy creation form.
 *
 * @package Custom_PTT\Taxonomy
 * @since 1.0.0
 * @version 1.0.0
 */
class Taxonomy_Form_Page implements Registerable {

	use Utilities;

	public const OPTION_NAME = 'Custom_PTT_taxonomy_settings';

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
			'Custom_PTT_taxonomy_settings_group',
			'Custom_PTT_taxonomy_settings',
			array( $this, 'validate_settings' )
		);

		add_settings_section(
			'Custom_PTT_taxonomy_settings_section',
			__( 'Taxonomy Settings', 'custom-post-types-taxonomies' ),
			null,
			'Custom_PTT_taxonomy_settings_page'
		);

		foreach ( $config as $key => $field ) {
			/**
			 * Let's skip args for now as this is a huge field list we need to
			 *  work further on it.
			 */
			if ( 'args' === $key ) {
				continue;
			}

			do_action( 'qm/debug', $key );

			$title = $this->format_snake_case_to_title_case( $key );

			// Dynamically add settings field based on config
			add_settings_field(
				'custom-post-types-taxonomies-' . $key,
				__( $title, 'custom-post-types-taxonomies' ),
				array( $this, 'render_field' ),
				'Custom_PTT_taxonomy_settings_page',
				'Custom_PTT_taxonomy_settings_section',
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
			'custom-post-types-taxonomies',  // Corrected parent slug
			__( 'Add New Taxonomy', 'custom-post-types-taxonomies' ),
			__( 'Add New', 'custom-post-types-taxonomies' ),
			'manage_options',
			'add-custom-post-types-taxonomies-taxonomies',
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
		require __DIR__ . '/templates/custom-ptt-taxonomies-form.php';
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
		$options = get_option( self::OPTION_NAME );

		// Extract the key and value from the args array
		$key   = $args['key'];
		$field = $args['value'];

		// Use the key to get the current value of this field from the options
		$value = $options[ $key ] ?? '';

		// Assuming each field in the config has a 'type' property
		$type = $field['type'] ?? 'text';  // Default to text if type is not set

		// Render the input field
		echo "<input type='" . esc_attr( $type ) . "' name='Custom_PTT_taxonomy_settings[" . esc_attr( $key ) . "]' value='" . esc_attr( $value ) . "'>";

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
