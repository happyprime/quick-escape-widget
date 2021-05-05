<?php

namespace QuickEscapeWidget\Setup;

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_scripts' );
add_action( 'widgets_init', __NAMESPACE__ . '\register_widget' );
add_action( 'admin_init', __NAMESPACE__ . '\check_redirect_storage' );
add_action( 'template_redirect', __NAMESPACE__ . '\handle_request', 1 );

/**
 * Enqueue the script used by this plugin.
 */
function enqueue_scripts() {
	wp_enqueue_script(
		'quick-escape-widget',
		plugin_dir_url( __DIR__ ) . '/js/src/quick.js',
		array(),
		'0.0.2',
		true
	);
}

/**
 * Register the widget provided by this plugin.
 */
function register_widget() {
	\register_widget( 'QuickEscapeWidget\Widget\Quick_Escape_Widget' );
}

/**
 * Move a stored-as-transient redirect URL into its more permanent position
 * if one is found.
 */
function check_redirect_storage() {
	$redirect_url = get_option( '_transient_qew_redirect_url', '' );

	if ( '' !== $redirect_url ) {
		update_option( 'qew_redirect_url', esc_url_raw( $redirect_url ) );
		delete_option( '_transient_qew_redirect_url' );
	}
}

/**
 * Handle a redirect request registered in the browser's
 * history by a click on the Quick Escape link.
 */
function handle_request() {
	if ( isset( $_REQUEST['qew'] ) && 1 === (int) $_REQUEST['qew'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$redirect_url = get_option( 'qew_redirect_url', '' );

		// The old transient data may still be in place.
		if ( '' === $redirect_url ) {
			$redirect_url = get_option( '_transient_qew_redirect_url', 'https://google.com' );
		}

		wp_redirect( $redirect_url ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
		exit;
	}
}
