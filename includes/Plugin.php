<?php

declare( strict_types=1 );

namespace Custom_PTT;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Custom_PTT\Infrastructure\Registerable;
use Exception;

/**
 * Main plugin class for Custom PTT Plugin.
 *
 * This class is the primary entry point for the plugin, responsible for
 * initializing and managing the plugin's services and functionality.
 *
 * @package   Custom_PTT
 * @since     0.1.0-alpha
 */
class Plugin {

	/**
	 * Container instance.
	 *
	 * @var Container
	 *
	 * @since 0.1.0-alpha
	 */
	protected Container $container;

	/**
	 * Plugin constructor.
	 *
	 * @param Container $container Container instance.
	 *
	 * @since 0.1.0-alpha
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
	 *
	 * @since 0.1.0-alpha
	 */
	public function boot(): self {
		$this->register_hooks();
		$this->register_services();

		return $this;
	}

	/**
	 * Register the services.
	 *
	 * @return void
	 * @throws Exception
	 *
	 * @since 0.1.0-alpha
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
	 *
	 * @since 0.1.0-alpha
	 */
	public function register_hooks(): void {
		register_activation_hook( CUSTOM_PTT_FILE, array( $this, 'on_activation' ) );
		register_deactivation_hook( CUSTOM_PTT_FILE, array( $this, 'on_deactivation' ) );
	}

	/**
	 * Activation hook callback.
	 *
	 * @return void
	 *
	 * @since 0.1.0-alpha
	 */
	public function on_activation(): void {
	}

	/**
	 * Deactivation hook callback.
	 *
	 * @return void
	 *
	 * @since 0.1.0-alpha
	 */
	public function on_deactivation(): void {
		wp_cache_flush();
		flush_rewrite_rules();
	}

	/**
	 * Uninstall hook callback.
	 *
	 * @return void
	 *
	 * @since 0.1.0-alpha
	 */
	public static function on_uninstall(): void {
	}
}
