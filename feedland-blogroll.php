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
		'bootstrap-js',
		'https://s3.amazonaws.com/scripting.com/code/includes/bootstrap.min.js',
	);

	wp_enqueue_script(
		'feedland-blogroll',
		'https://code.scripting.com/blogroll/blogroll.js',
	);

	wp_localize_script(
		'feedland-blogroll',
		'BLOGROLL_OPTIONS',
		array(
			'title'                   => 'Test Blogroll',
			'urlBlogrollOpml'         => 'https://feedland.social/opml?screenname=davewiner&catname=blogroll',
			'urlFeedlandViewBlogroll' => 'https://feedland.social/?username=davewiner&catname=blogroll', 
			'idWhereToAppend'         => 'divBlogrollContainer',
			'maxItemsInBlogroll'      => 40,
		)
	);

	wp_enqueue_style(
		'bootstrap',
		'https://s3.amazonaws.com/scripting.com/code/includes/bootstrap.css',
	);

	wp_enqueue_style(
		'feedland-basic',
		'https://s3.amazonaws.com/scripting.com/code/includes/basic/styles.css',
		array( 'bootstrap' )
	);

	wp_enqueue_style(
		'feedland-blogroll',
		'https://s3.amazonaws.com/scripting.com/code/blogroll/blogroll.css',
		array( 'bootstrap' )
	);

	wp_enqueue_style(
		'fontawesome',
		'https://s3.amazonaws.com/scripting.com/code/fontawesome/css/all.css',
	);

	wp_enqueue_style(
		'feedland-blogroll-custom',
		'https://s3.amazonaws.com/scripting.com/code/feedland/home/misc.css'
	);
}

add_action( 'wp_enqueue_scripts', 'feedland_blogroll_enqueue_scripts' );

/**
 * Outputs the blogroll container. `id` must match what's defined in `appConsts.idWhereToAppend`.
 * 
 * @return string
 */
function feedland_blogroll_shortcode(): string {
	return '<div id="divBlogrollContainer" tabindex="0"></div><script>$=jQuery;blogroll(BLOGROLL_OPTIONS);</script>';
}

add_shortcode( 'feedland-blogroll', 'feedland_blogroll_shortcode' );
