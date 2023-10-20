<?php

declare( strict_types=1 );

namespace Custom_PTT\Post_Type;

use Custom_PTT\Infrastructure\Registerable;

/**
 * Post_Type class for Custom PTT Plugin.
 *
 * This class is responsible for registering and managing a custom post type
 * for the Custom PTT plugin.
 *
 * @package   Custom_PTT
 * @copyright Copyright (C) 2023-2023, Custom PTT - freibergergarcia@gmail.com
 * @link      https://www.github.com/freibergergarcia/custom-post-types-taxonomies
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
