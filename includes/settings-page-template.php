<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// $uploaded_file_url is still available here if you kept the transient for it
// and it's passed from render_main_page_content().
?>
<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <p>Welcome to the **One Builder Website Assistant** dashboard!</p>
    <p>Here you can upload an image to your custom folder: `wp-content/uploads/onebuilder/`.</p>

    <hr>

    <h2>Upload New Image</h2>
    <form method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <?php
        wp_nonce_field( 'onebuilder_assistant_upload_action', 'onebuilder_assistant_upload_nonce' );
        ?>
        <input type="hidden" name="action" value="onebuilder_assistant_upload">

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="onebuilder_assistant_image"><?php esc_html_e( 'Select Image:', 'onebuilder-website-assistant' ); ?></label></th>
                    <td><input type="file" id="onebuilder_assistant_image" name="onebuilder_assistant_image" accept="image/jpeg, image:png, image:gif"></td>
                </tr>
            </tbody>
        </table>
        <?php submit_button( esc_html__( 'Save', 'onebuilder-website-assistant' ) ); ?>
    </form>

    <?php
    // Display the last uploaded image if available (using the transient if you decided to keep it for this)
    if ( $uploaded_file_url ) {
        echo '<h3>' . esc_html__( 'Last Uploaded Image:', 'onebuilder-website-assistant' ) . '</h3>';
        echo '<img src="' . esc_url( $uploaded_file_url ) . '" style="max-width:300px; height:auto; display:block; margin-bottom:10px;" alt="' . esc_attr( basename( $uploaded_file_url ) ) . '">';
        echo '<p>URL: <a href="' . esc_url( $uploaded_file_url ) . '" target="_blank">' . esc_html( $uploaded_file_url ) . '</a></p>';
    }
    ?>
</div>