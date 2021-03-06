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

namespace whatsontap\post_types;

class TapLists {
	public function __construct() {
		add_action( 'init', array( $this, 'create' ), 20 );
		add_action( 'add_meta_boxes_' . WIC_PLUGIN_PREFIX . '-tap-lists', array( $this, 'register_meta' ) );
		add_action( 'save_post_' . WIC_PLUGIN_PREFIX . '-tap-lists', array( $this, 'save_meta' ) );

		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', function() {
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-sortable' );
			} );
		}
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
			'supports'              => array( 'title', 'author', 'thumbnail' ),
		);
		register_post_type( WIC_PLUGIN_PREFIX . '-tap-lists', $args );
	}

	// register our custom fields
	public function register_meta() {
		add_meta_box( WIC_PLUGIN_PREFIX . '_template_meta_box',
			__( 'Tap Information' ),
			array( $this, 'create_meta_box' ),
			WIC_PLUGIN_PREFIX . '-tap-lists',
			'normal',
			'high'
		);
	}

	// create our custom fields html markup
	public function create_meta_box() {
		global $post;
		wp_nonce_field( basename( __FILE__ ), WIC_PLUGIN_PREFIX . '_meta_box_nonce' );

		$taplist = new \whatsontap\TapList( $post->ID );
		$taps = $taplist->taps;

		?>
        <div class="whats-on-tap">
            <input type="hidden" name="taps" value="<?php echo esc_attr( $taps ); ?>" />
            <div class="inside">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4><?php _e( 'Add a Tap to List', FP_TEXT_DOMAIN ); ?></h4>
                            <label id="find-taps">
                                <input class="input-ajaxfind" placeholder="<?php _e( 'Search Taps', WIC_TEXT_DOMAIN ); ?>"
                                       data-find='["<?php
                                       echo wp_create_nonce( WIC_PLUGIN_PREFIX . '-input_ajax_finder' );
                                       ?>","-taps","post_title","_brewery"]'
                                       type="text" id="find-taps" autocomplete="off" data-js-input-ajaxfinder />
                            </label>
                            <h4><?php _e( 'Bulk Import', WIC_PLUGIN_PREFIX ); ?></h4>
                            <p>
                                <?php ob_start(); ?>
                                Write one beer name per line, do not include brewery or any other information, that will be added later.
                                <?php echo _e( ob_get_clean(), WIC_PLUGIN_PREFIX ); ?>
                            </p>
                            <p>
                                <textarea cols="40" rows="20" data-js-bulkimport-textarea></textarea>
                            </p>
                            <p>
                                <button type="button"
                                        data-nonce="<?php echo wp_create_nonce( WIC_PLUGIN_PREFIX . '-input_ajax_importer' ); ?>"
                                        data-js-bulkimport-btn><?php _e( 'Import', WIC_TEXT_DOMAIN ); ?></button>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <h4><?php _e( 'Tap List', FP_TEXT_DOMAIN ); ?></h4>
                            <ul class="list-suggested" data-js-list-sortable>
                                <?php $found = ''; $cnt = 0; foreach ( $taps as $tap ) :
                                    $found .= $tap->tap->ID . ';';
                                    /** @var $tap \whatsontap\Tap */
                                    $tap->display_sort_tap( $cnt );
                                    $cnt++;
                                endforeach; ?>
                            </ul>
                        </div>
                        <input type="hidden" name="<?php echo WIC_PLUGIN_PREFIX; ?>_taps" value="<?php echo $found; ?>"data-js-input-found />
                    </div>
                </div>
            </div>
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

		$taps = explode( ';', rtrim( $_POST[ WIC_PLUGIN_PREFIX . '_taps' ], ';' ) );
		update_post_meta( $post_id, '_' . WIC_PLUGIN_PREFIX . '_taps', $taps );
	}
}