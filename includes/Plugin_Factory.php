<?php

declare(strict_types=1);

namespace Custom_PTT;

use DI\Container;

/**
 * Plugin Factory class.
 *
 * It can decide if a new plugin instance is needed, or we can
 * Read more about why this is preferable to a singleton
 *
 * @package Custom_PTT
 * @since 0.1.0-alpha
 * @see https://www.alainschlesser.com/singletons-shared-instances/
 */
class Plugin_Factory {

	/**
	 * Create and return an instance of the plugin.
	 *
	 * This always returns a shared instance.
	 *
	 * @return Plugin Plugin instance.
	 * @since 0.1.0-alpha
	 */
	public static function create( Container $container ): Plugin {
		static $plugin = null;

		if ( null === $plugin ) {
			$plugin = new Plugin( $container );
		}

		return $plugin;
	}
}
