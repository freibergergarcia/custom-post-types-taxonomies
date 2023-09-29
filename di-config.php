<?php
/**
 * Dependency Injection Configuration for the WordPress Related Plugin.
 *
 * This file defines the services and dependencies of the WordPress Related plugin.
 *
 * @package   WORDPRESS_RELATED
 * @copyright Copyright (C) 2023-2023, WordPress Related - freibergergarcia@gmail.com
 * @link      https://www.github.com/freibergergarcia/wordpress-related
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 *
 * @since 1.0.0
 */

declare( strict_types=1 );

namespace WordPress_Related;

use function DI\create;

return [
	'taxonomy'  => create( 'WordPress_Related\Taxonomy\Taxonomy' ),
	'post_type' => create( 'WordPress_Related\Post_Type\Post_Type' ),
];
