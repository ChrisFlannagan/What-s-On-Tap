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
	public $abv;
	public $brewery;
	public $website;
	public $date;
	public $colors = array();

	public function __construct( $tap_id = 0, $args = array() ) {
        $defaults = array(
            'beer_color' => '#000',
            'brewery_color' => '#7e7e7e',
        );
        $this->colors = wp_parse_args( $args, $defaults );

		$this->tap = null;
		$this->load_tap( $tap_id );
	}

	public function load_tap( $tap_id ) {
		$tap = get_post( $tap_id );
		if ( is_a( $tap, 'WP_Post' ) && $tap->post_type == WIC_PLUGIN_PREFIX . '-taps' ) {
			$this->tap = $tap;
            $this->abv = get_post_meta( $tap->ID, '_' . WIC_PLUGIN_PREFIX . '_abv', true );
            $this->brewery = get_post_meta( $tap->ID, '_' . WIC_PLUGIN_PREFIX . '_brewery', true );
            $this->website = get_post_meta( $tap->ID, '_' . WIC_PLUGIN_PREFIX . '_website_text', true );
            $this->date    = get_post_meta( $tap->ID, '_' . WIC_PLUGIN_PREFIX . '_date', true );
		}
	}

	public function json() {
	    return array(
	            'beer' => $this->tap->post_title,
                'brewery' => $this->brewery,
                'abv' => $this->abv,
                'date' => $this->date,
                'website' => $this->website,
            );
    }

	public function display_tap( $wrap = [ '', '' ] ) {
	    /** @var $tap \WP_Post */
		$tap = $this->tap;
		if ( is_a( $tap, 'WP_Post' ) ) :
			echo ( isset( $wrap[0] ) ? $wrap[0] : '' ); ?>
			<div class="beer">
                <i></i>
				<span class="title" style="color: <?php echo $this->colors['beer_color']; ?>"><?php echo $tap->post_title; ?></span>
				<?php if ( '' != $this->date ) : ?>
				<span class="subtitle"><?php
					echo __( 'Tapped', WIC_TEXT_DOMAIN ) . ' ';
					printf( _x( '%s ago',
						'%s = human-readable time difference',
						WIC_TEXT_DOMAIN ),
						human_time_diff( strtotime( $this->date ),
						current_time( 'timestamp' ) ) );
					?></span>
				<?php endif;?>
			</div>
			<div class="meta">
				<?php if ( '' != $this->brewery ) : ?>
				<span class="brewery" style="color: <?php echo $this->colors['brewery_color']; ?>"><?php echo esc_attr( $this->brewery ); ?></span>
				<?php endif; ?>
				<?php if ( '' != $this->abv ) : ?>
					<span class="abv"><?php echo esc_attr( $this->abv ); ?>%</span>
				<?php endif; ?>
				<?php if ( strpos( $this->website, 'http' ) == 0 ) : ?>
					<span class="website">
						<a href="<?php echo esc_url( $this->website ); ?>"><?php _e( 'More Info', WIC_TEXT_DOMAIN ); ?></a>
					</span>
				<?php endif; ?>
			</div>
		<?php echo ( isset( $wrap[1] ) ? $wrap[1] : '' ); endif;
	}

	public function display_sort_tap( $cnt = 0 ) {
        /**
         * @tap WP_Post
         */
        $tap = $this->tap; ?>
        <li data-cnt="<?php echo $cnt; ?>" onclick="" class="menuitem" data-id="<?php echo $tap->ID; ?>" data-js-suggest-result="">
            <?php echo $tap->post_title; ?>
            <span class="meta"><?php echo $this->brewery; ?></span> <span class="remove" onclick="remSuggested(this);">Remove</span>
        </li>
    <?php }

}