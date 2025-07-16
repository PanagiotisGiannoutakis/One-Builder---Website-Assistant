<?php
/**
 * Plugin Name: One Builder Website Assistant
 * Description: My awesome WordPress assistant plugin.
 * Version: 1.0
 * Author: Your Name
 * Text Domain: onebuilder-website-assistant
 *
 * @package OneBuilder
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants for paths
if ( ! defined( 'ONE_BUILDER_WEBSITE_ASSISTANT_DIR_PATH' ) ) {
    define( 'ONE_BUILDER_WEBSITE_ASSISTANT_DIR_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'ONE_BUILDER_ASSISTANT_UPLOAD_PATH' ) ) {
    $upload_dir_info = wp_upload_dir();
    define( 'ONE_BUILDER_ASSISTANT_UPLOAD_PATH', $upload_dir_info['basedir'] . '/onebuilder/' );
    define( 'ONE_BUILDER_ASSISTANT_UPLOAD_URL', $upload_dir_info['baseurl'] . '/onebuilder/' );
}

// Include the main plugin class file
require_once ONE_BUILDER_WEBSITE_ASSISTANT_DIR_PATH . 'includes/class-onebuilder-assistant-admin.php';

// Instantiate the plugin class to start its functionality
new OneBuilderWebsiteAssistant();