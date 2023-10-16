<?php

namespace WordPress_Related\Config;

use Exception;

class Config_Loader_Exception extends Exception {

	/**
	 * @param string $message
	 */
	public function __construct( string $message ) {
		parent::__construct( $message );
	}
}
