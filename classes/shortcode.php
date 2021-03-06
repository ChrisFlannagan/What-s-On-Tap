<?php
/**
 * Class ShortCode
 *
 * PHP version 7.1
 *
 * @package whatsontap
 * @author Chris Flannagan <chris@flowpress.com>
 * @copyright 2017 FlowPress
 * @version 0.1
 * @since 0.1
 */

/**
 * This shortcode is used to display a tap listing in html
 */

namespace whatsontap;

class ShortCode {
	public function __construct() {
		add_shortcode( 'whatsontap', array( $this, 'display_taps' ) );
	}

	public function display_taps( $atts = array(), $content = null ) {
		$options = shortcode_atts( array(
			'taplist' => 0,
            'brewery_color' => '',
            'beer_color' => '',
		), $atts );
		ob_start();
		/* No matter what is passed we will create a Tap_List object and manually fill it or load from a post type */
		if ( strpos( $options['taplist'], ',' ) !== false ) {
			// Display a list of taps
		} elseif ( intval( $options['taplist'] ) == 0 ) {
			$tap_list = new TapList();
			$tap_list->load_list_with_all_taps();
			$tap_list->display_taps();
		} elseif ( intval( $options['taplist'] ) > 0 ) {
			// Display a Tap List posts listing
            $tap_list = new TapList( $options['taplist'], $options );
            $tap_list->display_taps();
		}

		echo ob_get_clean();
	}
}