=== Custom PTT ===
Contributors: freibergergarcia
Tags: custom post types, taxonomies, post types, custom taxonomies
Requires at least: 5.8
Tested up to: 6.8
Requires PHP: 8.0
Stable tag: 0.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple WordPress Plugin to extend the functionality of WordPress by creating Custom Taxonomies and Custom Post Types.

== Description ==

Once the plugin is activated, it doesn't generate any code to be placed anywhere in the `theme` or `plugin` files.  

**We initialize custom Post Types and custom Taxonomies by the use of WordPress hooks**.

= Key Features =
- WordPress VIP compatible
- Performance optimized with object caching
- Follows WordPress VIP coding standards
- Comprehensive error handling and logging
- Easily add Custom Taxonomies
- Easily add Custom Post Types
- Extend with built-in filters and actions

= Performance Features =
- Object caching for taxonomy and post type registrations
- Smart cache invalidation based on arguments
- Optimized database queries
- Proper error handling and logging

= Filters and Actions =

== Taxonomies ==

```php 
// Modify taxonomy registration arguments
apply_filters('custom_ptt_taxonomy_args', $args, $taxonomy_slug, $taxonomy_data);

// After taxonomies are registered
do_action('custom_ptt_registered_taxonomies', $taxonomies);
```

== Post Types ==

```php 
// Modify post type registration arguments
apply_filters('custom_ptt_post_type_args', $args, $post_type_key, $post_type_data);

// After post types are registered
do_action('custom_ptt_registered_post_types', $post_types);
```

== Installation ==

```bash
composer install
```

== Development ==

= Code Standards =
```bash
composer phpcs
composer phpcbf  # Auto-fix
```

= Testing =

PHP Unit Tests:
```bash
composer unit          # Run PHPUnit tests
composer coverage      # Generate coverage report
```

End-to-End Tests:
```bash
npm run test-e2e       # Run Playwright E2E tests
npm run test-e2e-headed # Run E2E tests with visible browser
npm run test-e2e-debug # Debug E2E tests step by step
```

All Tests:
```bash
npm run test           # Run both unit and E2E tests
```

WordPress Environment:
```bash
npm run start-env      # Start local WordPress environment
npm run stop-env       # Stop WordPress environment
npm run test-php       # Run unit tests in wp-env
```

== Changelog ==

= 0.2.0 =
* Added Container Singleton pattern for dependency injection
* Improved VIP compatibility and performance optimization
* Enhanced error handling and logging
* Added object caching for post types and taxonomies

== License ==

GNU General Public License v2.0 - see the [LICENSE](LICENSE) file for details.
