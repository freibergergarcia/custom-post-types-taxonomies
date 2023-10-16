<?php

declare( strict_types=1 );

namespace WordPress_Related;

/**
 * Trait Utilities
 *
 * A collection of utility methods for the WordPress Related plugin.
 *
 * This trait provides utility methods that can be reused across different
 * classes within the WordPress Related plugin.
 *
 * @package WordPress_Related
 *
 * @since 1.0.0
 */
trait Utilities {

	/**
	 * Format snake_case string to Title Case.
	 *
	 * This function takes a snake_case string, replaces underscores with spaces,
	 * and converts the first letter of each word to uppercase.
	 *
	 * @param string $snake_case_string The snake_case string to be formatted.
	 *
	 * @return string The formatted Title Case string.
	 */
	public function format_snake_case_to_title_case( string $snake_case_string ): string {
		$words             = str_replace( '_', ' ', $snake_case_string );  // Replace underscores with spaces
		$title_case_string = ucwords( $words );  // Convert the first letter of each word to uppercase

		return $title_case_string;
	}
}
