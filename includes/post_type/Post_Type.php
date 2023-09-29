<?php

declare( strict_types=1 );

namespace WordPress_Related\Post_Type;

use WordPress_Related\Infrastructure\Registerable;

/**
 * Post_Type class for WordPress Related Plugin.
 *
 * This class is responsible for registering and managing a custom post type
 * for the WordPress Related plugin.
 *
 * @package   WORDPRESS_RELATED
 * @copyright Copyright (C) 2023-2023, WordPress Related - freibergergarcia@gmail.com
 * @link      https://www.github.com/freibergergarcia/wordpress-related
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 * @since     1.0.0
 * @see       https://developer.wordpress.org/plugins/post-types/
 */
class Post_Type implements Registerable {

	/**
	 * Register the post type.
	 *
	 * @return void
	 */
	public function register(): void {
	}
}
