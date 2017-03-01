<?php
/**
 * Class WhatsOnTap
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
 * Initialize all of our necessary classes
 */

namespace whatsontap;

class WhatsOnTap {

	public static function init() {
		$taps = new post_types\Taps();
		$lists = new post_types\TapLists();
		$styles = new taxonomies\Styles();
		$shortcode = new ShortCode();
	}

}