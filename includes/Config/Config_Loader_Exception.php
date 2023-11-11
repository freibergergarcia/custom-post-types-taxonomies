<?php

declare(strict_types=1);

namespace Custom_PTT\Config;

use Exception;

/**
 * Config_Loader_Exception Class
 *
 * This class extends the base Exception class to represent exceptions
 * that are thrown during the configuration loading process.
 *
 * @package Custom_PTT\Config
 * @since 0.1.0-alpha
 */
class Config_Loader_Exception extends Exception {

	/**
	 * Constructor.
	 *
	 * Initializes the exception with a specific message.
	 *
	 * @param string $message The exception message.
	 *
	 * @since 0.1.0-alpha
	 */
	public function __construct( string $message ) {
		parent::__construct( $message );
	}
}
