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

	public function display_tap( $wrap = [ '', '' ] ) {
		$tap = $this->tap;
		if ( is_a( $tap, 'WP_Post' ) ) :
			$abv = get_post_meta( $tap->ID, '_' . WIC_PLUGIN_PREFIX . '_abv', true );
			$brewery = get_post_meta( $tap->ID, '_' . WIC_PLUGIN_PREFIX . '_brewery', true );
			$website = get_post_meta( $tap->ID, '_' . WIC_PLUGIN_PREFIX . '_website_text', true );
			$date    = get_post_meta( $tap->ID, '_' . WIC_PLUGIN_PREFIX . '_date', true );
			echo ( isset( $wrap[0] ) ? $wrap[0] : '' ); ?>
			<div class="_wic_taptitle">
				<span class="title"><?php echo $tap->post_title; ?></span>
				<?php if ( '' != $brewery ) : ?>
				<span class="subtitle"><?php
					echo __( 'Tapped', WIC_TEXT_DOMAIN ) . ' ';
					printf( _x( '%s ago',
						'%s = human-readable time difference',
						WIC_TEXT_DOMAIN ),
						human_time_diff( strtotime( $date ),
						current_time( 'timestamp' ) ) );
					?></span>
				<?php endif;?>
			</div>
			<div class="_wic_tapmeta">
				<?php if ( '' != $brewery ) : ?>
				<span class="brewery"><?php echo esc_attr( $brewery ); ?></span>
				<?php endif; ?>
				<?php if ( '' != $abv ) : ?>
					<span class="abv"><?php echo esc_attr( $abv ); ?>%</span>
				<?php endif; ?>
				<?php if ( strpos( $website, 'http' ) == 0 ) : ?>
					<span class="website">
						<a href="<?php echo esc_url( $website ); ?>"><?php _e( 'More Info', WIC_TEXT_DOMAIN ); ?></a>
					</span>
				<?php endif; ?>
			</div>
		<?php echo ( isset( $wrap[1] ) ? $wrap[1] : '' ); endif;
	}

}