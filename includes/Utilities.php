<?php

declare( strict_types=1 );

namespace Custom_PTT;

/**
 * Trait Utilities
 *
 * A collection of utility methods for the Custom PTT plugin.
 *
 * This trait provides utility methods that can be reused across different
 * classes within the Custom PTT plugin.
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
	 *
	 * @since 0.1.0-alpha
	 */
	public function format_snake_case_to_title_case( string $snake_case_string ): string {
		$words             = str_replace( '_', ' ', $snake_case_string );
		$title_case_string = ucwords( $words );

		return $title_case_string;
	}
}
