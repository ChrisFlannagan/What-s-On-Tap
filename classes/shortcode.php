<?php

namespace whatsontap;

class ShortCode {
	public function __construct() {
		add_shortcode( 'whatsontap', array( $this, 'display_taps' ) );
	}

	private function display_taps( $atts = array(), $content = null ) {
		$options = shortcode_atts( array(
			'tap-list' => 0,
		), $atts );
		
		return 'hi beautiful';

		/* No matter what is passed we will create a Tap_List object and manually fill it or load from a post type */
		if ( strpos( $options['tap-list'], ',' ) !== false ) {
			// Display a list of taps
		} elseif ( intval( $options['tap-list'] == 0 ) ) {
			$tap_list = new TapList();
			$tap_list->load_list_with_all_taps();
			ob_start();
			$tap_list->display_taps();
			return ob_get_clean();
		} elseif ( intval( $options['tap-list'] ) > 0 ) {
			// Display a tap list post type
		}
	}
}