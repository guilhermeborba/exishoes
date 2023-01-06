=== LA-Studio Core ===
Contributors: Duy Pham
Tags: core
Requires at least: 4.9
Tested up to: 4.9
Stable tag: 4.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


== Description ==
This plugin use only for LA-Studio theme with Elementor


== Changelog ==

= 2.0.13 =
Clear cache - ?lastudio_clear_cache
Disable Extensions - ?lastudio_disable_extensions=360,swatches,content_type

= 2.0.10 =
# Cache WP_Query
Cache WP_Query will cache posts from WP_Query through an automatic wrapper. It avoids extra DB requests and caches to object cache. Integrates with ElasticPress to cache those requests too!

## Supports

* [ElasticPress](https://wordpress.org/plugins/elasticpress/)
* [Pods](https://wordpress.org/plugins/pods/) - If active, Cache WP_Query will utilize Pods caches
* [Pods Alternative Cache](https://wordpress.org/plugins/pods-alternative-cache/) - through Pods support, you can cache your query results to the filesystem which is handy for those hosting situations with small (or no) object cache setups

## Usage

In your `WP_Query` arguments, set `'cache_wp_query' => true`.

```php
$args = array(
	'post_type'      => 'posts',
	/* complicated arguments with meta lookups, date queries, etc */
	'cache_wp_query' => true, // Cache this query
);

$my_query = new WP_Query( $args );
```

In your plugin or theme, add the post types you want to support for caching (this is primarily for cache clearing purposes when a new post is published). By default, no post types are cached until you add support for one.

```php
/**
 * Add support to my post types for Cache WP Query
 */
function my_cache_wp_query_post_types() {

	add_post_type_support( 'post', 'cache_wp_query' );
	add_post_type_support( 'my_cpt', 'cache_wp_query' );

}
add_filter( 'init', 'my_cache_wp_query_post_types' );
```

Searches are cached by default (assuming post types are all in the list above), but this can be disabled.

```php
// Disable default search caching
add_filter( 'cache_wp_query_search', '__return_false' );
```

ElasticPress caching is enabled by default, but this can be disabled.

```php
// Disable ElasticPress caching
add_filter( 'cache_wp_query_elasticpress', '__return_false' );
```

= 2.0.1 =
^ Moving the editor.min.js file to upload folder

= 1.0.0 =