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
		'feedland_blogroll_options',
		array(
			'sanitize_callback' => 'feedland_blogroll_validate_options',
		)
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
			'description' => esc_html__( 'The title appears at the top of the blogroll box. It defaults to My Blogroll.', 'feedland-blogroll' ),
		)
	);

	add_settings_field(
		'feedland_blogroll_username',
		__( 'FeedLand username', 'feedland-blogroll' ),
		'feedland_blogroll_settings_field_callback',
		'feedland_blogroll_settings',
		'feedland_blogroll_settings_section',
		array(
			'label_for'   => 'feedland_blogroll_username',
			'type'        => 'text',
			'name'        => 'feedland_blogroll_username',
			'class'       => 'regular-text',
			'description' => esc_html__( 'The username of the account whose blogroll you want shown. (Required)', 'feedland-blogroll' ),
		)
	);

	add_settings_field(
		'feedland_blogroll_server',
		__( 'FeedLand server', 'feedland-blogroll' ),
		'feedland_blogroll_settings_field_callback',
		'feedland_blogroll_settings',
		'feedland_blogroll_settings_section',
		array(
			'label_for'   => 'feedland_blogroll_server',
			'type'        => 'url',
			'name'        => 'feedland_blogroll_server',
			'class'       => 'regular-text',
			'placeholder' => FEEDLAND_DEFAULT_SERVER,
			'description' => esc_html__( 'The server that account is on. (Defaults to feedland.com, required)', 'feedland-blogroll' ),
		)
	);

	add_settings_field(
		'feedland_blogroll_category',
		__( 'Category (optional)', 'feedland-blogroll' ),
		'feedland_blogroll_settings_field_callback',
		'feedland_blogroll_settings',
		'feedland_blogroll_settings_section',
		array(
			'label_for'   => 'feedland_blogroll_category',
			'type'        => 'text',
			'name'        => 'feedland_blogroll_category',
			'class'       => 'regular-text',
			'description' => esc_html__( 'You can choose only to have feeds from a specific category in the blogroll, if you want all the feeds you\'ve subscribed to, leave this blank.', 'feedland-blogroll' ),
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

	$value = $options[ $args['name'] ] ?? '';

	switch ( $args['type'] ) {
		case 'text':
		case 'url':
			printf(
				'<input type="%1$s" id="%2$s" name="feedland_blogroll_options[%2$s]" value="%3$s" class="%4$s" placeholder="%5$s" />',
				esc_attr( $args['type'] ),
				esc_attr( $args['name'] ),
				esc_attr( $value ),
				esc_attr( $args['class'] ),
				esc_attr( $args['placeholder'] ?? '' )
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

/**
 * Validate options before saving
 *
 * @param array $input Options to validate
 *
 * @return array Validated options
 */
function feedland_blogroll_validate_options( array $input ): array {
	// Validate server URL
	if ( ! empty( $input['feedland_blogroll_server'] ) ) {
		// Ensure the server URL is properly formatted and sanitize it
		if ( filter_var( $input['feedland_blogroll_server'], FILTER_VALIDATE_URL ) ) {
			$input['feedland_blogroll_server'] = esc_url_raw( $input['feedland_blogroll_server'] );
		} else {
			add_settings_error(
				'feedland_blogroll_settings',
				'feedland_blogroll_server',
				esc_html__( 'The FeedLand server URL is not valid.', 'feedland-blogroll' )
			);
			$input['feedland_blogroll_server'] = FEEDLAND_DEFAULT_SERVER;
		}
	} else {
		$input['feedland_blogroll_server'] = FEEDLAND_DEFAULT_SERVER;
	}

	// Sanitize and validate username
	if ( ! empty( trim( $input['feedland_blogroll_username'] ) ) ) {
		$input['feedland_blogroll_username'] = sanitize_text_field( $input['feedland_blogroll_username'] );
	} else {
		add_settings_error(
			'feedland_blogroll_settings',
			'feedland_blogroll_username',
			sprintf(
				/* translators: %s: Default username placeholder */
				esc_html__( 'The username cannot be empty.', 'feedland-blogroll' ),
				FEEDLAND_DEFAULT_USERNAME
			)
		);
		$input['feedland_blogroll_username'] = FEEDLAND_DEFAULT_USERNAME;
	}

	// Now that we have sanitized server and username, we can perform the remote check
	$user_endpoint = sprintf( '%1$sisuserindatabase?screenname=%2$s', $input['feedland_blogroll_server'], $input['feedland_blogroll_username'] );

	$request = wp_remote_get( $user_endpoint );

	// Handle error in communication with the server
	if ( is_wp_error( $request ) ) {
		add_settings_error(
			'feedland_blogroll_settings',
			'feedland_blogroll_server',
			esc_html__( 'There was an error communicating with the server. Resetting to default server.', 'feedland-blogroll' )
		);
		$input['feedland_blogroll_server'] = FEEDLAND_DEFAULT_SERVER;
	} else {
		$response = json_decode( wp_remote_retrieve_body( $request ), true );

		// Verify that the username exists in the database
		if ( ! $response['flInDatabase'] ) {
			add_settings_error(
				'feedland_blogroll_settings',
				'feedland_blogroll_username',
				esc_html__( 'The username provided is not associated with a FeedLand account.', 'feedland-blogroll' )
			);

			$input['feedland_blogroll_username'] = FEEDLAND_DEFAULT_USERNAME;
		}
	}
	if ( ! empty( trim( $input['feedland_blogroll_category'] ) ) ) {
		// Validate category, since username is now validated or default.
		$request = wp_remote_get(
			add_query_arg(
				array(
					'url' => rawurlencode( feedland_get_opml_url( $input['feedland_blogroll_category'] ) ),
				),
				FEEDLAND_DEFAULT_SERVER . 'getfeedlistfromopml'
			)
		);

		if ( is_wp_error( $request ) ) {
			add_settings_error(
				'feedland_blogroll_settings',
				'feedland_blogroll_category',
				esc_html__( 'There was an error communicating with the server.', 'feedland-blogroll' )
			);

			$input['feedland_blogroll_category'] = FEEDLAND_DEFAULT_CATEGORY;
		}

		$response = json_decode( wp_remote_retrieve_body( $request ), true );
		error_log( print_r( $request, TRUE ) );

		// If the response contains a message, the category does not exist.
		if ( array_key_exists( 'message', $response ) ) {
			add_settings_error(
				'feedland_blogroll_settings',
				'feedland_blogroll_category',
				sprintf(
					/* translators: %s: Default category placeholder */
					esc_html__( 'The user does not have that category in their feed.', 'feedland-blogroll' ),
					FEEDLAND_DEFAULT_CATEGORY
				)
			);

			$input['feedland_blogroll_category'] = FEEDLAND_DEFAULT_CATEGORY;
		}
	}

	return $input;
}
