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

    public $id = 0;
    public $title = '';
	public $taps = array();
	public $colors = array();
	
	public function __construct( $tap_list_id = 0, $args = array() ) {
	    $defaults = array(
	            'beer_color' => '#000',
	            'brewery_color' => '#7e7e7e',
        );
	    $this->colors = wp_parse_args( $args, $defaults );

	    $this->id = $tap_list_id;
	    $this->title = get_the_title( $this->id );
		$this->load_list( $tap_list_id );
	}
	
	public function load_list( $tap_list_id = 0 ) {
	    $this->taps = array();

        $taps = (Array) get_post_meta( $this->id, '_' . WIC_PLUGIN_PREFIX . '_taps', true );
        if ( count( $taps ) == 1 && '' == $taps[0] ) $taps = array();

        foreach ( $taps as $tap ) {
            if ( 0 < intval( $tap ) ) {
                $tap = new Tap( $tap, $this->colors );
                /** @var $tap \WP_Post */
                if ( is_a( $tap->tap, '\WP_Post') && $tap->tap->post_type == WIC_PLUGIN_PREFIX . '-taps') {
                    $this->taps[] = $tap;
                }
            }
        }
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

	public function json_taps() {
	    $json_taps = array();
	    foreach( $this->taps as $tap ) {
	        $json_taps[] = $tap->json();
        }
        return $json_taps;
    }

	public function display_taps() { ?>
        <ul class="list-displaytaps">
		<?php foreach( $this->taps as $tap ) :
				$tap->display_tap( [ '<li class="tap">', '</li>' ] ) ;
		endforeach; ?>
		</ul>
	<?php }
}