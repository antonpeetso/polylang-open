<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function polylang_open_editor_assets() {
	wp_enqueue_script(
		'polylang_open_editor-js',
		plugins_url( '/dist/editor.js', dirname( __FILE__ ) ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' )
	);
}
add_action( 'enqueue_block_editor_assets', 'polylang_open_editor_assets' );