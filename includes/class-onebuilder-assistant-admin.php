<?php
/**
 * File: class-onebuilder-assistant-admin.php
 * Main administration class for the One Builder Website Assistant plugin.
 *
 * @package OneBuilder
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include the Trait that handles admin menus and page rendering.
require_once ONE_BUILDER_WEBSITE_ASSISTANT_DIR_PATH . 'includes/trait-onebuilder-assistant-admin-menus.php';

class OneBuilderWebsiteAssistant {

    use OneBuilder_Assistant_Admin_Menus;

    /**
     * Constructor for the plugin class.
     * Registers all plugin hooks.
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_main_menu_page' ) );
        add_action( 'admin_post_onebuilder_assistant_upload', array( $this, 'handle_form_submission' ) );
        add_action( 'admin_notices', array( $this, 'display_admin_notices' ) );
    }

    /**
     * Handles the form submission for image upload.
     * This logic remains within the main class.
     */
    public function handle_form_submission() {
        $redirect_url = admin_url( 'admin.php?page=onebuilder-assistant' );

        $has_error = false;
        $error_message_for_debug = '';

        // Nonce verification for security
        if ( ! isset( $_POST['onebuilder_assistant_upload_nonce'] ) || ! wp_verify_nonce( $_POST['onebuilder_assistant_upload_nonce'], 'onebuilder_assistant_upload_action' ) ) {
            $has_error = true;
            $error_message_for_debug = 'Security check failed.';
        }

        // Ensure the user has the capability to upload files
        if ( ! $has_error && ! current_user_can( 'upload_files' ) ) {
            $has_error = true;
            $error_message_for_debug = 'Insufficient permissions.';
        }

        // Only proceed with file handling if no error found so far
        if ( ! $has_error ) {
            if ( ! empty( $_FILES['onebuilder_assistant_image']['name'] ) && $_FILES['onebuilder_assistant_image']['error'] === UPLOAD_ERR_OK ) {
                $uploaded_file      = $_FILES['onebuilder_assistant_image'];
                $file_name          = sanitize_file_name( basename( $uploaded_file['name'] ) );
                $target_upload_path = ONE_BUILDER_ASSISTANT_UPLOAD_PATH . $file_name;

                $file_type = wp_check_filetype( $file_name, null );
                if ( ! $file_type['ext'] || ! in_array( $file_type['ext'], array( 'jpg', 'jpeg', 'png', 'gif' ) ) ) {
                    $has_error = true;
                    $error_message_for_debug = 'Invalid file type.';
                }

                if ( ! $has_error && ! is_dir( ONE_BUILDER_ASSISTANT_UPLOAD_PATH ) ) {
                    if ( ! wp_mkdir_p( ONE_BUILDER_ASSISTANT_UPLOAD_PATH ) ) {
                        $has_error = true;
                        $error_message_for_debug = 'Could not create upload directory.';
                    }
                }

                if ( ! $has_error ) {
                    if ( move_uploaded_file( $uploaded_file['tmp_name'], $target_upload_path ) ) {
                        // Success: Add 'upload_success=true' to the URL
                        wp_redirect( add_query_arg( 'upload_success', 'true', $redirect_url ) );

                        // === ΑΛΛΑΓΗ ΕΔΩ: Χρησιμοποιούμε update_option αντί για set_transient ===
                        update_option( 'onebuilder_assistant_uploaded_file_url', ONE_BUILDER_ASSISTANT_UPLOAD_URL . $file_name );
                        exit; // Exit after successful redirect
                    } else {
                        $has_error = true;
                        $error_message_for_debug = 'Error moving uploaded file.';
                    }
                }
            } else {
                // PHP upload errors
                if ( ! empty( $_FILES['onebuilder_assistant_image']['error'] ) ) {
                    $has_error = true;
                    switch ( $_FILES['onebuilder_assistant_image']['error'] ) {
                        case UPLOAD_ERR_INI_SIZE: $error_message_for_debug = 'File exceeds ini size.'; break;
                        case UPLOAD_ERR_FORM_SIZE: $error_message_for_debug = 'File exceeds form size.'; break;
                        case UPLOAD_ERR_PARTIAL: $error_message_for_debug = 'File upload partial.'; break;
                        case UPLOAD_ERR_NO_FILE: $error_message_for_debug = 'No file uploaded.'; break;
                        case UPLOAD_ERR_NO_TMP_DIR: $error_message_for_debug = 'Missing temp dir.'; break;
                        case UPLOAD_ERR_CANT_WRITE: $error_message_for_debug = 'Can\'t write file.'; break;
                        case UPLOAD_ERR_EXTENSION: $error_message_for_debug = 'PHP extension stopped upload.'; break;
                        default: $error_message_for_debug = 'Unknown PHP upload error.'; break;
                    }
                } else {
                    $has_error = true;
                    $error_message_for_debug = 'No file selected.';
                }
            }
        }

        // If an error occurred, redirect with generic error message
        if ( $has_error ) {
            error_log( 'One Builder Website Assistant Upload Error: ' . $error_message_for_debug );
            wp_redirect( add_query_arg( 'upload_error', 'true', $redirect_url ) );
            exit;
        }
    }
}