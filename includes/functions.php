<?php

function get_taplists( $ids = array() ) {
    $args = array(
        'post_type'      => WIC_PLUGIN_PREFIX . '-tap-lists',
        'post_status'    => 'publish',
        'posts_per_page' => -1
    );

    $lists = get_posts( $args );
    $taplists = array();
    foreach ( $lists as $list ) {
        $taplists[] = new \whatsontap\TapList( $list->ID );
    }

    return $taplists;
}