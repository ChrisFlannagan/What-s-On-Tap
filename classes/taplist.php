<?php
/**
 * Class TapList
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
 * Use to create instances of a tap list.  Tap lists contain an array of Tap objects and provides functionality
 * for display and managing a tap list.
 */

namespace whatsontap;

class TapList {
	
	public $taps = array();
	
	public function __construct( $tap_list_id = 0 ) {
		$this->load_list( $tap_list_id );
	}
	
	public function load_list( $tap_list_id = 0 ) {
		
	}

	public function load_list_by_ids( $taps = array() ) {
		foreach ( $taps as $tap ) {
			$tap = new Tap( $tap );
		}
	}

	public function load_list_with_all_taps() {
		$taps = new \WP_Query( array(
			'post_type' => WIC_PLUGIN_PREFIX . '-taps',
			'per_page' => -1,
			'post_status' => 'publish',
			'fields' => 'ids',
		) );

		foreach( $taps->posts as $tap ) {
			$this->taps[] = new Tap( $tap );
		}
	}

	public function display_taps() { ?>
		<ul class="_wic_list-taps">
		<?php foreach( $this->taps as $tap ) :
				$tap->display_tap( [ '<li class="tap">', '</li>' ] ) ;
		endforeach; ?>
		</ul>
	<?php }
}