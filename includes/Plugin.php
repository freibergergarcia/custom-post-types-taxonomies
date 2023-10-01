<?php

declare( strict_types=1 );

namespace WordPress_Related;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use WordPress_Related\Infrastructure\Registerable;
use Exception;

/**
 * Main plugin class for WordPress Related Plugin.
 *
 * This class is the primary entry point for the plugin, responsible for
 * initializing and managing the plugin's services and functionality.
 *
 * @package   WORDPRESS_RELATED
 * @copyright Copyright (C) 2023-2023, WordPress Related - freibergergarcia@gmail.com
 * @link      https://www.github.com/freibergergarcia/wordpress-related
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 * @since     1.0.0
 */
class Plugin {

	/**
	 * Container instance.
	 *
	 * @var Container
	 */
	protected Container $container;

	/**
	 * Plugin constructor.
	 *
	 * @param Container $container Container instance.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;
	}

	/**
	 * Bootstraps the plugin, registers hooks and services.
	 *
	 * This method is intended to be called to kickstart the plugin setup.
	 * It registers the necessary hooks and initializes the services required for the plugin to work.
	 *
	 * @throws Exception If a service fails to register.
	 */
	public function boot(): void {
		$this->register_hooks();
		$this->register_services();
	}

	/**
	 * Initialize the plugin hooks | actions and filters.
	 *
	 * @return void
	 */
	public function init(): void {
	}

	/**
	 * Register the services.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function register_services(): void {
		$services = $this->container->getKnownEntryNames();
		foreach ( $services as $service ) {
			try {
				$service_instance = $this->container->get( $service );
			} catch ( DependencyException | NotFoundException $exception ) {
				// @todo Log the exception.
				die( 'Something went wrong: ' . esc_html( $exception->getMessage() ) );
			}

			if ( $service_instance instanceof Registerable ) {
				$service_instance->register();
			}
		}
	}

	/**
	 * Register Infrastructure hooks.
	 *
	 * @return void
	 */
	public function register_hooks(): void {
		register_activation_hook( WORDPRESS_RELATED_FILE, array( $this, 'on_activation' ) );
		register_deactivation_hook( WORDPRESS_RELATED_FILE, array( $this, 'on_deactivation' ) );
	}

	/**
	 * Activation hook callback.
	 *
	 * @return void
	 */
	public function on_activation(): void {
	}

	/**
	 * Deactivation hook callback.
	 *
	 * @return void
	 */
	public function on_deactivation(): void {
		wp_cache_flush();
		wp_rewrite_flush();
	}

	/**
	 * Uninstall hook callback.
	 *
	 * @return void
	 */
	public static function on_uninstall(): void {
	}
}
