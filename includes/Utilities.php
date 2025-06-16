<?php

declare( strict_types=1 );

namespace Custom_PTT;

/**
 * Trait Utilities
 *
 * A collection of utility methods for the Custom PTT plugin.
 *
 * @package Custom_PTT
 * @since 0.1.0-alpha
 */
trait Utilities {

	/**
	 * Format snake_case string to Title Case.
	 *
	 * This function takes a snake_case string, replaces underscores with spaces,
	 * and converts the first letter of each word to uppercase.
	 *
	 * @param string $snake_case_string The snake_case string to be formatted.
	 * @return string The formatted Title Case string.
	 * @throws \InvalidArgumentException If string is empty.
	 *
	 * @since 0.1.0-alpha
	 */
	public function format_snake_case_to_title_case( string $snake_case_string ): string {
		if ( empty( $snake_case_string ) ) {
			throw new \InvalidArgumentException( 'String cannot be empty' );
		}

		return ucwords( str_replace( '_', ' ', $snake_case_string ) );
	}
}
