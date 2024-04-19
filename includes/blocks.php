<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the blocks with Gutenberg.
 *
 * @return  void
 */
function feedland_blogroll_register_block() {
	register_block_type(
		FEEDLAND_BLOGROLL_PATH . 'blocks/build/feedland-blogroll',
		array(
			'render_callback' => 'feedland_blogroll_shortcode',
		)
	);
}
