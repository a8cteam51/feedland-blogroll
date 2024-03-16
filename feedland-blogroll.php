<?php
/**
 * Plugin Name:       FeedLand Blogroll
 * Description:       Show a Blogroll on your site.
 * Requires at least: 6.1
 * Requires PHP:      7.4
 * Version:           0.0.1
 * Author:            WordPress.com Special Projects
 * Author URI:        https://wpspecialprojects.wordpress.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       feedland-blogroll
 *
 * @package           feedland-blogroll
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueues the scripts and styles needed for the blogroll.
 *
 * @return void
 */
function feedland_blogroll_enqueue_scripts(): void {
	wp_enqueue_script(
		'feedland-basic',
		'https://s3.amazonaws.com/scripting.com/code/includes/basic/code.js',
		array( 'jquery' ),
	);

	wp_enqueue_script(
		'feedland-api',
		'https://s3.amazonaws.com/scripting.com/code/feedland/home/api.js',
	);

	wp_enqueue_script(
		'feedland-misc',
		'https://s3.amazonaws.com/scripting.com/code/feedland/home/misc.js',
	);

	wp_enqueue_script(
		'feedland-blogroll',
		'https://code.scripting.com/blogroll/blogroll.js',
	);

	wp_localize_script(
		'feedland-blogroll',
		'appConsts',
		array(
			'title'                   => 'Chuck\'s Blogroll',
			'idWhereToAppend'         => 'divBlogrollContainer',
			'urlFeedlandServer'       => 'https://feedland.social/',
			'urlSocketServer'         => 'wss://feedland.social/',
			'urlBlogrollOpml'         => 'https://feedland.com/opml?screenname=cagrimmett&catname=blogroll',
			'urlFeedlandViewBlogroll' => 'https://feedland.com/?username=cagrimmett&catname=blogroll',
			'maxItemsInBlogroll'      => 40,
			'flShowSocketMessages'    => true,
			'flBlogrollUpdates'       => true,
		)
	);

	wp_enqueue_style(
		'feedland-blogroll',
		'https://s3.amazonaws.com/scripting.com/code/blogroll/blogroll.css',
	);
}

add_action( 'wp_enqueue_scripts', 'feedland_blogroll_enqueue_scripts' );

/**
 * Outputs the blogroll container. `id` must match what's defined in `appConsts.idWhereToAppend`.
 * 
 * @return string
 */
function feedland_blogroll_shortcode(): string {
	return '<div id="divBlogrollContainer"></div>';
}

add_shortcode( 'feedland-blogroll', 'feedland_blogroll_shortcode' );
