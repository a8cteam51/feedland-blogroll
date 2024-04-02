<?php
/**
 * Plugin Name:       FeedLand Blogroll
 * Description:       Show a Blogroll on your site.
 * Requires at least: 6.1
 * Requires PHP:      7.4
 * Version:           1.0.0
 * Author:            WordPress.com Special Projects
 * Author URI:        https://wpspecialprojects.wordpress.com
 * Update URI:        https://github.com/a8cteam51/feedland-blogroll
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       feedland-blogroll
 *
 * @package           feedland-blogroll
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( defined( 'FEEDLAND_BLOGROLL_PATH' ) ) {
	return; // Return if another copy of the plugin is activated
}

define( 'FEEDLAND_BLOGROLL_PATH', plugin_dir_path( __FILE__ ) );

require_once 'includes/settings.php';
require_once 'includes/self-update.php';

/**
 * Actions and shortcodes here
 */
add_action( 'wp_enqueue_scripts', 'feedland_blogroll_enqueue_scripts' );
add_shortcode( 'feedland-blogroll', 'feedland_blogroll_shortcode' );
add_action( 'admin_menu', 'feedland_blogroll_add_admin_menu' );
add_action( 'admin_init', 'feedland_blogroll_settings_init' );
register_activation_hook( __FILE__, 'feedland_blogroll_default_options' );
add_filter( 'plugin_action_links_feedland-blogroll/feedland-blogroll.php', 'feedland_blogroll_add_action_links' );

/**
 * Enqueues the scripts and styles needed for the blogroll.
 *
 * @return void
 */
function feedland_blogroll_enqueue_scripts(): void {

	wp_register_script(
		'feedland-basic',
		'https://s3.amazonaws.com/scripting.com/code/includes/basic/code.js',
		array( 'jquery' ),
		'1.0.0',
		false,
	);

	wp_register_script(
		'feedland-api',
		'https://s3.amazonaws.com/scripting.com/code/feedland/home/api.js',
		array(),
		'1.0.0',
		false,
	);

	wp_register_script(
		'feedland-misc',
		'https://s3.amazonaws.com/scripting.com/code/feedland/home/misc.js',
		array(),
		'1.0.0',
		false,
	);

	wp_register_script(
		'bootstrap-js',
		'https://s3.amazonaws.com/scripting.com/code/includes/bootstrap.min.js',
		array(),
		'1.0.0',
		false,
	);

	wp_register_script(
		'feedland-blogroll',
		'https://code.scripting.com/blogroll/blogroll.js',
		array(),
		'1.0.0',
		false,
	);

	wp_register_style(
		'bootstrap',
		'https://s3.amazonaws.com/scripting.com/code/blogroll/smallbootstrap.css',
		array(),
		'1.0.0',
		false,
	);

	wp_register_style(
		'feedland-basic',
		'https://s3.amazonaws.com/scripting.com/code/includes/basic/styles.css',
		array( 'bootstrap' ),
		'1.0.0',
		false,
	);

	wp_register_style(
		'feedland-blogroll',
		'https://s3.amazonaws.com/scripting.com/code/blogroll/blogroll.css',
		array( 'bootstrap' ),
		'1.0.0',
		false,
	);

	wp_register_style(
		'fontawesome',
		'https://s3.amazonaws.com/scripting.com/code/fontawesome/css/all.css',
		array(),
		'1.0.0',
		false,
	);

	wp_register_style(
		'feedland-blogroll-custom',
		'https://s3.amazonaws.com/scripting.com/code/feedland/home/misc.css',
		array(),
		'1.0.0',
		false,
	);

	wp_register_style(
		'feedland-google-fonts-ubuntu',
		'//fonts.googleapis.com/css?family=Ubuntu:400,500i,700',
		array(),
		'1.0.0',
		false,
	);

	wp_register_style(
		'feedland-google-fonts-rancho',
		'//fonts.googleapis.com/css?family=Rancho',
		array(),
		'1.0.0',
		false,
	);

	$options = get_option( 'feedland_blogroll_options' );

	wp_localize_script(
		'feedland-blogroll',
		'BLOGROLL_OPTIONS',
		array(
			'title'                   => $options['feedland_blogroll_title'],
			'flDisplayTitle'          => $options['feedland_blogroll_flDisplayTitle'],
			'urlBlogrollOpml'         => $options['feedland_blogroll_urlBlogrollOpml'],
			'urlFeedlandViewBlogroll' => $options['feedland_blogroll_urlFeedlandViewBlogroll'],
			'maxItemsInBlogroll'      => 40,
		)
	);
}

/**
 * Outputs the blogroll container.
 *
 * @return string
 */
function feedland_blogroll_shortcode(): string {

	wp_enqueue_script( 'feedland-basic' );
	wp_enqueue_script( 'feedland-api' );
	wp_enqueue_script( 'feedland-misc' );
	wp_enqueue_script( 'bootstrap-js' );
	wp_enqueue_script( 'feedland-blogroll' );
	wp_enqueue_style( 'bootstrap' );
	wp_enqueue_style( 'feedland-basic' );
	wp_enqueue_style( 'feedland-blogroll' );
	wp_enqueue_style( 'fontawesome' );
	wp_enqueue_style( 'feedland-blogroll-custom' );
	wp_enqueue_style( 'feedland-google-fonts-ubuntu' );
	wp_enqueue_style( 'feedland-google-fonts-rancho' );

	return '<div id="idBlogrollContainer" class="divBlogrollContainer" tabindex="0"></div><script>$=jQuery;blogroll(BLOGROLL_OPTIONS);</script>';
}


/**
 * Sets default options for the plugin upon activation.
 */
function feedland_blogroll_default_options(): void {
	// Set default option values if not already set
	$defaults = array(
		'feedland_blogroll_title'                   => __( 'My Blogroll', 'feedland-blogroll' ),
		'feedland_blogroll_flDisplayTitle'          => '1',
		'feedland_blogroll_urlBlogrollOpml'         => 'https://feedland.social/opml?screenname=davewiner&catname=blogroll',
		'feedland_blogroll_urlFeedlandViewBlogroll' => 'https://feedland.social/?username=davewiner&catname=blogroll',
	);

	$options = get_option( 'feedland_blogroll_options' );

	// If options don't exist, set default values.
	if ( false === $options ) {
		update_option( 'feedland_blogroll_options', $defaults );
	} else {
		// Merge with defaults to ensure all options are set, even if new ones have been added since the user last saved.
		$options = wp_parse_args( $options, $defaults );
		update_option( 'feedland_blogroll_options', $options );
	}
}
