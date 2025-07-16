<?php
/**
 * File: trait-onebuilder-assistant-admin-menus.php
 * Defines methods for handling admin menus and page rendering.
 *
 * @package OneBuilder
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

trait OneBuilder_Assistant_Admin_Menus {

    /**
     * Callback function to add the main dashboard menu page.
     */
    public function add_main_menu_page() {
        add_menu_page(
            esc_html__( 'One Builder', 'onebuilder-website-assistant' ),
            esc_html__( 'One Builder', 'onebuilder-website-assistant' ),
            'manage_options',
            'onebuilder-assistant',
            array( $this, 'render_main_page_content' ),
            'dashicons-clipboard',
            25
        );
    }

    /**
     * Callback function to render the content of the main menu page.
     * This method includes the separate template file.
     */
    public function render_main_page_content() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'onebuilder-website-assistant' ) );
        }

        // === ΑΛΛΑΓΗ ΕΔΩ: Χρησιμοποιούμε get_option αντί για get_transient ===
        $uploaded_file_url = get_option( 'onebuilder_assistant_uploaded_file_url', '' ); // Default to empty string if not set

        require_once ONE_BUILDER_WEBSITE_ASSISTANT_DIR_PATH . 'includes/settings-page-template.php';

        // === ΑΦΑΙΡΟΥΜΕ ΤΟ DELETE TRANSIENT ===
        // Δεν χρειάζεται delete_transient πλέον, αφού είναι option.
    }

    /**
     * Displays admin notices based on URL query arguments.
     * This method is hooked to 'admin_notices'.
     */
    public function display_admin_notices() {
        // Only display notices on our specific admin page
        if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'onebuilder-assistant' ) {
            return;
        }

        // Check for success message
        if ( isset( $_GET['upload_success'] ) && $_GET['upload_success'] === 'true' ) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Image uploaded successfully!', 'onebuilder-website-assistant' ) . '</p></div>';
        }

        // Check for error message (just check for existence, not specific code)
        if ( isset( $_GET['upload_error'] ) ) {
            echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Image upload failed. Please try again.', 'onebuilder-website-assistant' ) . '</p></div>';
        }
    }
}