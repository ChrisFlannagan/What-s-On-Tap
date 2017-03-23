<?php
/**
 * Class AjaxFinder
 */

/**
 * This handles ajax requests that need to return a list of posts
 */

namespace whatsontap\ajax;

class AjaxFinder {
	private $d; // for storing our POST data for cleaner code

	public function __construct() {
		$this->d = $_POST;
		add_action( 'wp_ajax_input_ajaxfinder', array( $this, 'handler' ) );
	}

	public function handler() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX
		     && check_ajax_referer( WIC_PLUGIN_PREFIX . '-input_ajax_finder', 'nonce' )
		) {
			global $wpdb;
			$field = sanitize_text_field( $this->d['field'] );

			$sql = $wpdb->prepare(
				"
				 SELECT * FROM $wpdb->posts 
				 WHERE ( post_type = %s AND $field LIKE %s ) OR 
				 ID IN (
				 	SELECT post_id FROM $wpdb->postmeta WHERE
				 	meta_key = %s AND meta_value LIKE %s
				 );
				 ",
				array(
					WIC_PLUGIN_PREFIX . $this->d['find'],
					'%' . $this->d['val'] . '%',
					'_' . WIC_PLUGIN_PREFIX . $this->d['meta'],
					'%' . $this->d['val'] . '%',
				)
			);
			$posts = $wpdb->get_results( $sql, ARRAY_A );

			$response = array();
			foreach ( $posts as $post ) {
				$meta = '';
				if ( isset( $this->d['meta'] ) && '' != $this->d['meta'] ) {
					$meta = get_post_meta( $post['ID'], '_' . WIC_PLUGIN_PREFIX . $this->d['meta'], true );
				}

				$response[] = array(
					'id' => $post['ID'],
					'title' => $post['post_title'],
					'meta' => $meta,
				);
			}

			echo json_encode( $response );
		} else {
			echo '0';
		}
		die();
	}
}