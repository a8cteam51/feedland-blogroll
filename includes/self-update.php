<?php
add_filter( 'update_plugins_github.com', 'feedland_blogroll_self_update', 10, 3 );

/**
 * Check for updates to this plugin
 *
 * @param array|false $update      Array of update data.
 * @param array       $plugin_data Array of plugin data.
 * @param string      $plugin_file Path to plugin file.
 * @param string[]    $locales     Locale code.
 *
 * @return array|false Array of update data or false if no update available.
 */
function feedland_blogroll_self_update( $update, array $plugin_data, string $plugin_file ) {

	// only check this plugin
	if ( 'feedland-blogroll/feedland-blogroll.php' !== $plugin_file ) {
		return $update;
	}

	// already completed update check elsewhere
	if ( ! empty( $update ) ) {
		return $update;
	}

	// let's go get the latest version number from GitHub
	$response = wp_remote_get(
		'https://api.github.com/repos/a8cteam51/feedland-blogroll/releases/latest',
		array(
			'user-agent' => 'wpspecialprojects',
		)
	);

	if ( is_wp_error( $response ) ) {
		return false;
	} else {
		$output = json_decode( wp_remote_retrieve_body( $response ), true );
	}

	$new_version_number  = $output['tag_name'];
	$is_update_available = version_compare( $plugin_data['Version'], $new_version_number, '<' );

	if ( ! $is_update_available ) {
		return false;
	}

	$new_url     = $output['html_url'];
	$new_package = $output['assets'][0]['browser_download_url'];

	return array(
		'slug'    => $plugin_data['TextDomain'],
		'version' => $new_version_number,
		'url'     => $new_url,
		'package' => $new_package,
	);
}
