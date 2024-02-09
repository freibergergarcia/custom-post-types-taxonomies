<?php
/**
 * Interface Registerable.
 *
 * This code is adapted from the original work by Alain Schlesser in his project at
 * https://www.mwpd.io/
 *
 * @copyright 2019 Alain Schlesser
 * @license   MIT
 *
 * Adapted for the Custom PTT project.
 */

declare( strict_types=1 );

namespace Custom_PTT\Infrastructure;

/**
 * Something that can be registered.
 *
 * @since 0.1.0-alpha
 */
interface Registerable {

	/**
	 * Register the service.
	 *
	 * @since 0.1.0-alpha
	 */
	public function register(): void;
}
