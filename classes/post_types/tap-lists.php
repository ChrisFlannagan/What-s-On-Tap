<?php

namespace whatsontap\post_types;

class Tap_Lists {
	public function __construct() {
		add_action( 'init', array( $this, 'create' ) );
		add_action( 'add_meta_boxes_' . WIC_PLUGIN_PREFIX . '-tap-lists', array( $this, 'register_meta' ) );
		add_action( 'save_post_' . WIC_PLUGIN_PREFIX . '-tap-lists', array( $this, 'save_meta' ) );
	}

	// register our post type
	public function create() {
		$labels = array(
			'name'               => __( 'Tap List', WIC_TEXT_DOMAIN ),
			'singular_name'      => __( 'Tap List', WIC_TEXT_DOMAIN ),
			'menu_name'          => __( 'Tap Lists', WIC_TEXT_DOMAIN ),
			'name_admin_bar'     => __( 'Tap List', WIC_TEXT_DOMAIN ),
			'add_new'            => __( 'Add New', WIC_TEXT_DOMAIN ),
			'add_new_item'       => __( 'Add New Tap List', WIC_TEXT_DOMAIN ),
			'new_item'           => __( 'New Tap List', WIC_TEXT_DOMAIN ),
			'edit_item'          => __( 'Edit Tap List', WIC_TEXT_DOMAIN ),
			'view_item'          => __( 'View Tap List', WIC_TEXT_DOMAIN ),
			'all_items'          => __( 'All Tap Lists', WIC_TEXT_DOMAIN ),
			'search_items'       => __( 'Search Tap Lists', WIC_TEXT_DOMAIN ),
			'parent_item_colon'  => __( 'Parent Tap Lists:', WIC_TEXT_DOMAIN ),
			'not_found'          => __( 'No Tap Lists found.', WIC_TEXT_DOMAIN ),
			'not_found_in_trash' => __( 'No Tap Lists found in Trash.', WIC_TEXT_DOMAIN )
		);

		$args = array(
			'labels'                => $labels,
			'description'           => __( 'Description.', WIC_TEXT_DOMAIN ),
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'query_var'             => true,
			'rewrite'               => array( 'slug' => WIC_PLUGIN_PREFIX . '-whats-on-tap-list' ),
			'capability_type'       => 'post',
			'has_archive'           => true,
			'hierarchical'          => false,
			'menu_position'         => null,
			'show_in_rest'          => true,
			'rest_base'             => WIC_PLUGIN_PREFIX . '_whats_on_tap_list',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
		);
		register_post_type( WIC_PLUGIN_PREFIX . '-tap-lists', $args );
	}
}