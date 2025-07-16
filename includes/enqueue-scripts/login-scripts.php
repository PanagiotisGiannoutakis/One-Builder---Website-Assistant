<?php
/**
 * Enqueue custom login styles for the login page.
 *
 * @package OneBuilder
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue custom styles for the login page.
 *
 * @return void
 */
function onebuilder_login_scripts() {
	wp_enqueue_style( 'onebuilder-login-css', ONE_BUILDER_WEBSITE_ASSISTANT_DIR_URL . 'assets/login-styles.css', array(), ONE_BUILDER_WEBSITE_ASSISTANT_VERSION );
}
add_action( 'login_enqueue_scripts', 'onebuilder_login_scripts' );
