<?php

declare( strict_types=1 );

namespace WordPress_Related;

use DI\Container;

/**
 * Plugin class.
 *
 * @package WordPress_Related
 * @since 0.0.1
 * @version 0.0.1
 * @see https://developer.wordpress.org/plugins/
 */
class Plugin {

	protected Container $container;

	public function __construct( Container $container ) {
		$this->container = $container;
	}

	public function init(): void {
	}
}
