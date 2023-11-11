<?php

declare(strict_types=1);

namespace Custom_PTT\Config;

use Exception;
use Custom_PTT\Infrastructure\Registerable;

/**
 * Config_Loader Class
 *
 * This class is responsible for loading configuration files for the Custom PTT plugin.
 * It implements the Registerable interface, which ensures its registration method is called
 * during the plugin's boot process. The class constructor accepts the path to the configuration
 * directory and the name of the configuration file to load, and provides a load method to load
 * the configuration file and return its contents as an array.
 *
 * @package Custom_PTT\Config
 * @since 0.1.0-alpha
 */
class Config_Loader implements Registerable {

	/**
	 * The path to the configuration directory.
	 *
	 * @var string
	 */
	private string $config_dir;

	/**
	 * The name of the configuration file to load.
	 *
	 * @var string
	 */
	private string $file;

	/**
	 * Config_Loader constructor.
	 *
	 * @param string $config_dir The path to the configuration directory.
	 * @param string $file The name of the configuration file to load.
	 *
	 * @since 0.1.0-alpha
	 */
	public function __construct( string $config_dir, string $file ) {
		$this->config_dir = $config_dir;
		$this->file       = $file;
	}

	/**
	 * Registers the Config_Loader with the WordPress hook system.
	 *
	 * @since 0.1.0-alpha
	 * @return void
	 */
	public function register(): void {
	}

	/**
	 * Load a configuration file.
	 *
	 * @return array The configuration data.
	 *
	 * @throws Config_Loader_Exception If the configuration file could not be loaded.
	 * @throws Exception
	 * @since 0.1.0-alpha
	 */
	public function load(): array {
		$path = $this->config_dir . '/' . $this->file;

		if ( ! file_exists( $path ) ) {
			throw new Config_Loader_Exception( sprintf( 'Configuration file not found: %s', esc_html( $this->file ) ) );
		}

		$json   = file_get_contents( $path );
		$config = json_decode( $json, true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			throw new Config_Loader_Exception( sprintf( 'Invalid JSON in configuration file: %s', esc_html( json_last_error_msg() ) ) );
		}

		return $config;
	}
}
