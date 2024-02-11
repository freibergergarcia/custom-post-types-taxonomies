<?php

declare( strict_types=1 );

namespace Custom_PTT;

use DI\Container;
use DI\ContainerBuilder;
use Exception;

/**
 * Container_Singleton class.
 *
 * This class is a placeholder for a singleton container instance.
 *
 * @package Custom_PTT
 * @since 0.2.1
 */
class Container_Singleton {
	/**
	 * The Container instance.
	 *
	 * @var Container|null
	 *
	 * @since 0.2.1
	 */
	private static ?Container $container = null;

	/**
	 * Prevent class from being instantiated.
	 * @since 0.2.1
	 */
	private function __construct() {}

	/**
	 * Get the Container instance.
	 *
	 * @return Container
	 * @throws Exception
	 *
	 * @since 0.2.1
	 */
	public static function get_instance(): Container {
		if ( null === static::$container ) {
			$container_builder = new ContainerBuilder();
			$container_builder->addDefinitions( __DIR__ . '/../config/di-config.php' );
			static::$container = $container_builder->build();
		}

		return static::$container;
	}
}
