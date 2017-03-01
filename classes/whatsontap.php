<?php
namespace whatsontap;

class WhatsOnTap {

	public static function init() {
		$taps = new post_types\Taps();
		$lists = new post_types\TapLists();
		$styles = new taxonomies\Styles();
		$shortcode = new ShortCode();
	}

}