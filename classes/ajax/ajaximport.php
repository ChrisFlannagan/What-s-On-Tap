<?php
/**
 * Class AjaxImport
 */

/**
 * This handles ajax requests that create new taps
 */

namespace whatsontap\ajax;

class AjaxImport {
    private $d; // for storing our POST data for cleaner code

    public function __construct() {
        $this->d = $_POST;
        add_action( 'wp_ajax_input_bulkimport', array( $this, 'handler' ) );
    }

    public function handler() {
        if ( current_user_can( 'manage_options' ) && defined( 'DOING_AJAX' ) && DOING_AJAX
            && check_ajax_referer( WIC_PLUGIN_PREFIX . '-input_ajax_importer', 'nonce' )
        ) {
            $tap_id = wp_insert_post( array(
                'post_title'    => wp_strip_all_tags( $_POST['tap'] ),
                'post_type'     => WIC_PLUGIN_PREFIX . '-taps',
                'post_status'   => 'publish',
            ) );

            if ( $tap_id > 0 ) {
                update_post_meta( $tap_id, '_' . WIC_PLUGIN_PREFIX . '_brewery', sanitize_text_field( $_POST['brewery'] ) );
                update_post_meta( $tap_id, '_' . WIC_PLUGIN_PREFIX . '_abv', floatval( $_POST['abv'] ) );
            }

            echo $tap_id;
        } else {
            echo '0';
        }
        die();
    }
}