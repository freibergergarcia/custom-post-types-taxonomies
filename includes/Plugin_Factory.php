<?php

declare( strict_types=1 );

namespace WordPress_Related;

use DI\Container;

/**
 * Plugin Factory class.
 *
 * It can decide if a new plugin instance is needed, or we can
 * Read more about why this is preferable to a singleton
 *
 * @package WordPress_Related
 * @since 0.0.1
 * @version 0.0.1
 * @see https://www.alainschlesser.com/singletons-shared-instances/
 */
class Plugin_Factory {

	protected Container $container;

	/**
	 * Create and return an instance of the plugin.
	 *
	 * This always returns a shared instance.
	 *
	 * @return Plugin Plugin instance.
	 */
	public static function create( Container $container ): Plugin {
		static $plugin = null;

		if ( null === $plugin ) {
			$plugin = new Plugin( $container );
		}

		return $plugin;
	}
}
