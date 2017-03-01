<?php
/**
 * Class Taps
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
 * Used to generate our WIC_PLUGIN_PREFIX-taps post type.  All information for individual taps are stored as post meta.
 */

namespace whatsontap\post_types;

class Taps {
	public function __construct() {
		add_action( 'init', array( $this, 'create' ) );
		add_action( 'add_meta_boxes_' . WIC_PLUGIN_PREFIX . '-taps', array( $this, 'register_meta' ) );
		add_action( 'save_post_' . WIC_PLUGIN_PREFIX . '-taps', array( $this, 'save_meta' ) );
	}

	// register our post type
	public function create() {
		$labels = array(
			'name'               => __( 'Tap', WIC_TEXT_DOMAIN ),
			'singular_name'      => __( 'Tap', WIC_TEXT_DOMAIN ),
			'menu_name'          => __( 'Taps', WIC_TEXT_DOMAIN ),
			'name_admin_bar'     => __( 'Tap', WIC_TEXT_DOMAIN ),
			'add_new'            => __( 'Add New', WIC_TEXT_DOMAIN ),
			'add_new_item'       => __( 'Add New Tap', WIC_TEXT_DOMAIN ),
			'new_item'           => __( 'New Tap', WIC_TEXT_DOMAIN ),
			'edit_item'          => __( 'Edit Tap', WIC_TEXT_DOMAIN ),
			'view_item'          => __( 'View Tap', WIC_TEXT_DOMAIN ),
			'all_items'          => __( 'All Taps', WIC_TEXT_DOMAIN ),
			'search_items'       => __( 'Search Taps', WIC_TEXT_DOMAIN ),
			'parent_item_colon'  => __( 'Parent Taps:', WIC_TEXT_DOMAIN ),
			'not_found'          => __( 'No Taps found.', WIC_TEXT_DOMAIN ),
			'not_found_in_trash' => __( 'No Taps found in Trash.', WIC_TEXT_DOMAIN )
		);

		$args = array(
			'labels'                => $labels,
			'description'           => __( 'Description.', WIC_TEXT_DOMAIN ),
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
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-ui-datepicker-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css', true);
	}

	// create our custom fields html markup
	public function create_meta_box() {
		global $post;
		wp_nonce_field( basename( __FILE__ ), WIC_PLUGIN_PREFIX . '_meta_box_nonce' );

		$abv = get_post_meta( $post->ID, '_' . WIC_PLUGIN_PREFIX . '_abv', true );
		$brewery = get_post_meta( $post->ID, '_' . WIC_PLUGIN_PREFIX . '_brewery', true );
		$website = get_post_meta( $post->ID, '_' . WIC_PLUGIN_PREFIX . '_website_text', true );
		$date    = get_post_meta( $post->ID, '_' . WIC_PLUGIN_PREFIX . '_date', true );

		?>
		<div class="inside">
			<h4>Tap Date</h4>
			<p>
				<input type="text" name="<?php echo WIC_PLUGIN_PREFIX; ?>_date"
				       value="<?php echo esc_attr( $date ); ?>"
				       data-js-field-datepicker/>
				<script>
					jQuery(document).ready(function(){
						jQuery('[data-js-field-datepicker]').datepicker({
							dateFormat : 'yy-m-d'
						});
					});
				</script>
			</p>
			<h4>ABV</h4>
			<p>
				<input type="text" name="<?php echo WIC_PLUGIN_PREFIX; ?>_abv"
				       value="<?php echo esc_attr( $abv ); ?>" />
			</p>
			<h4>Brewery</h4>
			<p>
				<input type="text" name="<?php echo WIC_PLUGIN_PREFIX; ?>_brewery"
				       value="<?php echo esc_attr( $brewery ); ?>" />
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
		update_post_meta( $post_id, '_' . WIC_PLUGIN_PREFIX . '_date', sanitize_text_field( $_POST[ WIC_PLUGIN_PREFIX . '_date' ] ) );
		update_post_meta( $post_id, '_' . WIC_PLUGIN_PREFIX . '_brewery', sanitize_text_field( $_POST[ WIC_PLUGIN_PREFIX . '_brewery' ] ) );
		update_post_meta( $post_id, '_' . WIC_PLUGIN_PREFIX . '_abv', floatval( $_POST[ WIC_PLUGIN_PREFIX . '_abv' ] ) );

		$website = trim( sanitize_text_field( $_POST[ WIC_PLUGIN_PREFIX . '_website_text' ] ) );
		if ( strpos( $website, 'http' ) != 0 ) {
			$website = 'http://' . $website;
		}
		update_post_meta( $post_id, '_' . WIC_PLUGIN_PREFIX . '_website_text', $website );
	}
}