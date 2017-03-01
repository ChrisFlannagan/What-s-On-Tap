<?php

namespace whatsontap;

class Short_Code {
	public function __construct() {
		add_shortcode( 'whatsontap', array( $this, 'display' ) );
	}

	private function display( $atts ) {
		$options = shortcode_atts( array(
			'tap-list' => 0,
		), $atts );

		/* No matter what is passed we will create a Tap_List object and manually fill it or load from a post type */
		if ( strpos( $options['tap-list'], ',' ) !== false ) {
			// Display a list of taps
		} elseif ( intval( $options['tap-list'] == 0 ) ) {
			// Display all published taps
		} elseif ( intval( $options['tap-list'] ) > 0 ) {
			// Display a tap list post type
		}
	}
}