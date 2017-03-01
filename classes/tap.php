<?php
/**
 * Class Tap
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
 * Used for initializing the a tap object.  Taps must be tied to a post with post type WIC_PLUGIN_PREFIX-taps.
 */

namespace whatsontap;

class Tap {

	public $tap;

	public function __construct( $tap_id = 0 ) {
		$this->tap = null;
		$this->load_tap( $tap_id );
	}

	public function load_tap( $tap_id ) {
		$tap = get_post( $tap_id );
		if ( is_a( $tap, 'WP_Post' ) && $tap->post_type == WIC_PLUGIN_PREFIX . '-taps' ) {
			$this->tap = $tap;
		}
	}

	public function display_tap() {
		$tap = $this->tap;
		if ( is_a( $tap, 'WP_Post' ) ) : ?>
			<div class="<?php echo WIC_PLUGIN_PREFIX; ?>-tap-title">
				<?php echo $tap->post_title; ?>
			</div>
		<?php endif;
	}

}