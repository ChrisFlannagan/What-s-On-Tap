<?php

namespace whatsontap\post_types;

class Taps {
	public function __construct() {
		add_action( 'init', array( $this, 'create' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'taps_scripts' ) );
		add_action( 'add_meta_boxes_' . WIC_PLUGIN_PREFIX . '-taps', array( $this, 'register_meta' ) );
		add_action( 'save_post_' . WIC_PLUGIN_PREFIX . '-taps', array( $this, 'save_meta' ) );
	}

	// register our post type
	public function create() {
		$labels = array(
			'name'               => __( 'Tap' ),
			'singular_name'      => __( 'Tap' ),
			'menu_name'          => __( 'Taps' ),
			'name_admin_bar'     => __( 'Tap' ),
			'add_new'            => __( 'Add New' ),
			'add_new_item'       => __( 'Add New Tap' ),
			'new_item'           => __( 'New Tap' ),
			'edit_item'          => __( 'Edit Tap' ),
			'view_item'          => __( 'View Tap' ),
			'all_items'          => __( 'All Taps' ),
			'search_items'       => __( 'Search Taps' ),
			'parent_item_colon'  => __( 'Parent Taps:' ),
			'not_found'          => __( 'No Taps found.' ),
			'not_found_in_trash' => __( 'No Taps found in Trash.' )
		);

		$args = array(
			'labels'                => $labels,
			'description'           => __( 'Description.' ),
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'query_var'             => true,
			'rewrite'               => array( 'slug' => WIC_PLUGIN_PREFIX . '-whats-on-tap' ),
			'capability_type'       => 'post',
			'has_archive'           => true,
			'hierarchical'          => false,
			'menu_position'         => null,
			'show_in_rest'          => true,
			'rest_base'             => WIC_PLUGIN_PREFIX . '_whats_on_tap',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
			'taxonomies'            => array( 'category' ),
		);
		register_post_type( WIC_PLUGIN_PREFIX . '-taps', $args );
	}

	// register our custom fields
	public function register_meta() {
		add_meta_box( WIC_PLUGIN_PREFIX . '_template_meta_box',
			__( 'Tap Information' ),
			array( $this, 'create_meta_box' ),
			WIC_PLUGIN_PREFIX . '-taps',
			'side',
			'low'
		);
	}

	// Include needed scripts
	public function taps_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-ui' );
	}

	// create our custom fields html markup
	public function create_meta_box() {
		global $post;
		wp_nonce_field( basename( __FILE__ ), WIC_PLUGIN_PREFIX . '_meta_box_nonce' );// retrieve the _food_cholesterol current value

		$website = get_post_meta( $post->ID, '_' . WIC_PLUGIN_PREFIX . '_website_text', true );
		$date    = get_post_meta( $post->ID, '_' . WIC_PLUGIN_PREFIX . '_date', true );

		?>
		<div class="inside">
			<h4>Tap Date</h4>
			<p>
				<input type="text" name="<?php echo WIC_PLUGIN_PREFIX; ?>_date"
				       value="<?php echo esc_attr( $date ); ?>"
				       data-js-field-datepicker/>
			</p>
			<h4>Info Link</h4>
			<p>
				<input type="text" name="<?php echo WIC_PLUGIN_PREFIX; ?>_website_text"
				       value="<?php echo esc_url( $website ); ?>"/>
			</p>
		</div>
		<?php
	}

	// save our meta data
	public function save_meta( $post_id ) {
		if ( ! isset( $_POST[ WIC_PLUGIN_PREFIX . '_meta_box_nonce' ] ) || ! wp_verify_nonce( $_POST[ WIC_PLUGIN_PREFIX . '_meta_box_nonce' ], basename( __FILE__ ) ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		update_post_meta( $post_id, '_' . WIC_PLUGIN_PREFIX . '_date', sanitize_text_field( $_POST[ WIC_PLUGIN_PREFIX . '_date' ] ) );;

		$website = trim( sanitize_text_field( $_POST[ WIC_PLUGIN_PREFIX . '_website_text' ] ) );
		if ( strpos( $website, 'http' ) != 0 ) {
			$website = 'http://' . $website;
		}
		update_post_meta( $post_id, '_' . WIC_PLUGIN_PREFIX . '_website_text', $website );
	}
}