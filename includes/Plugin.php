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
	 * Initialize the plugin.
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
	public function register(): void {
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
}
