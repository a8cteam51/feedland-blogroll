<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Add menu item and page for the FeedLand Blogroll settings
 *
 * @return void
 */
function feedland_blogroll_add_admin_menu(): void {
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
function feedland_blogroll_settings_page(): void {
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
function feedland_blogroll_settings_init(): void {
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
			'class'     => 'regular-text', // Class for styling if needed
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
function feedland_blogroll_settings_section_callback(): void {
	echo '<p>' . esc_html__( 'Customize the FeedLand Blogroll settings.', 'feedland-blogroll' ) . '</p>';
}

/**
 * Settings field callback
 *
 * @param array $args Arguments passed from the settings field
 *
 * @return void
 */
function feedland_blogroll_settings_field_callback( array $args ): void {
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
 *
 * @return array An array of plugin action links with the new "Settings" link.
 */
function feedland_blogroll_add_action_links( array $links ): array {
	$settings_slug = 'feedland_blogroll_settings';
	$settings_link = '<a href="' . esc_url( get_admin_url( null, 'options-general.php?page=' . $settings_slug ) ) . '">' . esc_html__( 'Settings', 'feedland-blogroll' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
