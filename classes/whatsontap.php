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

		if ( isset( $_POST['ajax_finder'] ) ) {
			$ajaxhandler = new ajax\AjaxFinder();
		}
	}

	public static function enqueue() {
		wp_enqueue_script( WIC_PLUGIN_PREFIX . '-plugin-script', 
			WIC_PLUGIN_DIR . 'assets/js/plugin.min.js', array( 'jquery' ), WIC_VER, true );
		wp_localize_script( WIC_PLUGIN_PREFIX . '-plugin-script', 'form_handler', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		) );
	}

	public static function admin_enqueue() {
		wp_enqueue_script( WIC_PLUGIN_PREFIX . '-pluginadmin-script', 
			WIC_PLUGIN_DIR . 'assets/js/pluginadmin.min.js', array( 'jquery' ), WIC_VER, true );
		wp_localize_script( WIC_PLUGIN_PREFIX . '-pluginadmin-script', 'form_handler', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		) );
	}

}