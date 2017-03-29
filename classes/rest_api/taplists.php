<?php
/**
 * Class TapLists
 *
 * PHP version 7.1
 *
 * @package whatsontap\post_types
 * @author Chris Flannagan <chris@flowpress.com>
 * @copyright 2017 FlowPress
 * @version 0.1
 * @since 0.1
 */

/**
 * Used to generate our WIC_PLUGIN_PREFIX-tap-lists post type.  This will store a list of tap id's in post meta and
 * sorting methods for the taps.
 */

namespace whatsontap\rest_api;

class TapLists {

    public static function init() {
        register_rest_route( 'whatsontap/v1', '/taplist/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array( '\whatsontap\rest_api\TapLists', 'list_taps' ),
        ) );
    }

    public static function list_taps( $data ) {
        $posts = get_posts( array(
            'include' => array( $data['id'] ),
            'post_type' => WIC_PLUGIN_PREFIX . '-tap-lists',
        ) );

        if ( empty( $posts ) ) {
            return null;
        }

        $taplist = new \whatsontap\TapList( $posts[0]->ID );
        return $taplist->json_taps();
    }
}