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
 * Actions and shortcodes here
 **/

add_action( 'wp_enqueue_scripts', 'feedland_blogroll_enqueue_scripts' );
add_shortcode( 'feedland-blogroll', 'feedland_blogroll_shortcode' );
add_action( 'admin_menu', 'feedland_blogroll_add_admin_menu' );
add_action( 'admin_init', 'feedland_blogroll_settings_init' );
add_filter( 'plugin_action_links_feedland-blogroll/feedland-blogroll.php', 'feedland_blogroll_add_action_links' );

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

	// Set default option values if not already set
	$defaults = array(
		'feedland_blogroll_title'                   => __( 'My Blogroll', 'feedland-blogroll' ),
		'feedland_blogroll_flDisplayTitle'          => '1',
		'feedland_blogroll_urlBlogrollOpml'         => 'https://feedland.social/opml?screenname=davewiner&catname=blogroll',
		'feedland_blogroll_urlFeedlandViewBlogroll' => 'https://feedland.social/?username=davewiner&catname=blogroll',
		'feedland_blogroll_idWhereToAppend'         => 'divBlogrollContainer',
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

	wp_localize_script(
		'feedland-blogroll',
		'BLOGROLL_OPTIONS',
		array(
			'title'                   => $options['feedland_blogroll_title'],
			'flDisplayTitle'          => $options['feedland_blogroll_flDisplayTitle'],
			'urlBlogrollOpml'         => $options['feedland_blogroll_urlBlogrollOpml'],
			'urlFeedlandViewBlogroll' => $options['feedland_blogroll_urlFeedlandViewBlogroll'],
			'idWhereToAppend'         => $options['feedland_blogroll_idWhereToAppend'],
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

	wp_enqueue_style(
		'feedland-google-fonts-ubuntu',
		'//fonts.googleapis.com/css?family=Ubuntu:400,500i,700',
	);

	wp_enqueue_style(
		'feedland-google-fonts-rancho',
		'//fonts.googleapis.com/css?family=Rancho',
	);
}

/**
 * Outputs the blogroll container. `id` must match what's defined in `appConsts.idWhereToAppend`.
 *
 * @return string
 */
function feedland_blogroll_shortcode(): string {
	return '<div id="divBlogrollContainer" tabindex="0"></div><script>$=jQuery;blogroll(BLOGROLL_OPTIONS);</script>';
}

/**
 * Add menu item and page for the FeedLand Blogroll settings
 *
 * @return void
 */
function feedland_blogroll_add_admin_menu() {
	add_options_page(
		__( 'FeedLand Blogroll Settings', 'feedland-blogroll' ),
		__( 'FeedLand Blogroll', 'feedland-blogroll' ),
		'manage_options',
		'feedland_blogroll_settings',
		'feedland_blogroll_settings_page'
	);
}

/**
 * Display settings page content
 *
 * @return void
 */
function feedland_blogroll_settings_page() {
	?>
	<div class="wrap">
		<form action="options.php" method="POST">
			<?php
			settings_fields( 'feedland_blogroll_settings' );
			do_settings_sections( 'feedland_blogroll_settings' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Renders settings page on admin_init
 *
 * @return void
 */
function feedland_blogroll_settings_init() {
	register_setting(
		'feedland_blogroll_settings',
		'feedland_blogroll_options'
		// Optional: Add a sanitize callback function if needed for validation later
	);

	add_settings_section(
		'feedland_blogroll_settings_section',
		__( 'FeedLand Blogroll Settings', 'feedland-blogroll' ),
		'feedland_blogroll_settings_section_callback',
		'feedland_blogroll_settings'
	);

	add_settings_field(
		'feedland_blogroll_title',
		__( 'Title', 'feedland-blogroll' ),
		'feedland_blogroll_settings_field_callback',
		'feedland_blogroll_settings',
		'feedland_blogroll_settings_section',
		array(
			'label_for' => 'feedland_blogroll_title',
			'type'      => 'text',
			'name'      => 'feedland_blogroll_title',
			'class'     => 'regular-text',  // Class for styling if needed
		)
	);

	add_settings_field(
		'feedland_blogroll_flDisplayTitle',
		__( 'Display Title', 'feedland-blogroll' ),
		'feedland_blogroll_settings_field_callback',
		'feedland_blogroll_settings',
		'feedland_blogroll_settings_section',
		array(
			'label_for' => 'feedland_blogroll_flDisplayTitle',
			'type'      => 'checkbox',
			'name'      => 'feedland_blogroll_flDisplayTitle',
		)
	);

	add_settings_field(
		'feedland_blogroll_idWhereToAppend',
		__( 'Element ID to Append Blogroll', 'feedland-blogroll' ),
		'feedland_blogroll_settings_field_callback',
		'feedland_blogroll_settings',
		'feedland_blogroll_settings_section',
		array(
			'label_for' => 'feedland_blogroll_idWhereToAppend',
			'type'      => 'text',
			'name'      => 'feedland_blogroll_idWhereToAppend',
			'class'     => 'regular-text',
		)
	);

	add_settings_field(
		'feedland_blogroll_urlBlogrollOpml',
		__( 'Blogroll OPML URL', 'feedland-blogroll' ),
		'feedland_blogroll_settings_field_callback',
		'feedland_blogroll_settings',
		'feedland_blogroll_settings_section',
		array(
			'label_for' => 'feedland_blogroll_urlBlogrollOpml',
			'type'      => 'url',
			'name'      => 'feedland_blogroll_urlBlogrollOpml',
			'class'     => 'regular-text',
		)
	);

	add_settings_field(
		'feedland_blogroll_urlFeedlandViewBlogroll',
		__( 'Feedland View Blogroll URL', 'feedland-blogroll' ),
		'feedland_blogroll_settings_field_callback',
		'feedland_blogroll_settings',
		'feedland_blogroll_settings_section',
		array(
			'label_for' => 'feedland_blogroll_urlFeedlandViewBlogroll',
			'type'      => 'url',
			'name'      => 'feedland_blogroll_urlFeedlandViewBlogroll',
			'class'     => 'regular-text',
		)
	);

}

/**
 * Settings section callback, can optionally add descriptions here
 *
 * @return void
 */
function feedland_blogroll_settings_section_callback() {
	echo '<p>' . esc_html__( 'Customize the FeedLand Blogroll settings.', 'feedland-blogroll' ) . '</p>';
}

/**
 * Settings field callback
 *
 * @return void
 */
function feedland_blogroll_settings_field_callback( $args ) {
	$options = get_option( 'feedland_blogroll_options' );

	$value = isset( $options[ $args['name'] ] ) ? $options[ $args['name'] ] : '';

	switch ( $args['type'] ) {
		case 'text':
		case 'url':
			printf(
				'<input type="%1$s" id="%2$s" name="feedland_blogroll_options[%2$s]" value="%3$s" class="%4$s" />',
				esc_attr( $args['type'] ),
				esc_attr( $args['name'] ),
				esc_attr( $value ),
				esc_attr( $args['class'] )
			);
			break;
		case 'checkbox':
			printf(
				'<input type="%1$s" id="%2$s" name="feedland_blogroll_options[%2$s]" value="1" %3$s class="%4$s" />',
				esc_attr( $args['type'] ),
				esc_attr( $args['name'] ),
				checked( 1, $value, false ),
				esc_attr( $args['class'] )
			);
			break;
	}

	if ( ! empty( $args['description'] ) ) {
		printf( '<p class="description">%s</p>', esc_html( $args['description'] ) );
	}
}

/**
 * Adds a settings link to the plugin action links on the plugins page.
 *
 * @param array $links An array of plugin action links.
 * @return array An array of plugin action links with the new "Settings" link.
 */
function feedland_blogroll_add_action_links( $links ) {
	$settings_slug = 'feedland_blogroll_settings';
	$settings_link = '<a href="' . esc_url( get_admin_url( null, 'options-general.php?page=' . $settings_slug ) ) . '">' . esc_html__( 'Settings', 'feedland-blogroll' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
