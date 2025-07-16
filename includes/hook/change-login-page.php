<?php
/**
 * Changes the login page logo URL for WordPress.
 *
 * @package OneBuilder
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the URL for the login page logo.
 *
 * @return string The URL to be used for the login logo link.
 */
function onebuilder_login_logo_url() {
	return 'https://onebuilder.net';
}
add_filter( 'login_headerurl', 'onebuilder_login_logo_url' );
