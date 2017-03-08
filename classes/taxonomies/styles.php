<?php
/**
 * Class Styles
 *
 * PHP version 7.1
 *
 * @package whatsontap\taxonomies
 * @author Chris Flannagan <chris@flowpress.com>
 * @copyright 2017 FlowPress
 * @version 0.1
 * @since 0.1
 */

/**
 * Used to generate a styles taxonomy for the WIC_PLUGIN_PREFIX-taps post type.  This will generally be used for storing
 * styles of beer such as IPA, Stout, Pale Ale and so on and will generate an initial list of terms.
 */

namespace whatsontap\taxonomies;

class Styles {

	public $defaults;

	public function __construct() {
		$this->defaults = array(
			'IPA' => array(
				'description' => __( 'Bitter and hoppy, depending on hop variety lookout for pine, citrus and floral notes', WIC_TEXT_DOMAIN ),
				'slug' => WIC_PLUGIN_PREFIX . '-style-ipa',
			),
			'Stout' => array(
				'description' => __( 'Dark beer that can be sweet or dry', WIC_TEXT_DOMAIN ),
				'slug' => WIC_PLUGIN_PREFIX . '-style-stout',
			)
		);
		add_action( 'init', array( $this, 'create' ), 20 );
	}
	
	public function setup_defaults() {
		foreach ( $this->defaults as $key => $new_term ) {
			$new_term = term_exists( $new_term['slug'], WIC_PLUGIN_PREFIX . '-taps' );
			if ( $new_term === 0 || $new_term === null ) {
				wp_insert_term(
					$key, // the term
					WIC_PLUGIN_PREFIX . '-styles', // the taxonomy
					array(
						'description' => $new_term['description'],
						'slug'        => $new_term['slug'],
					)
				);
			}
		}
	}

	public function create() {
		register_taxonomy(
			WIC_PLUGIN_PREFIX . '-styles',
			WIC_PLUGIN_PREFIX . '-taps',
			array(
				'label'             => __( 'Styles', WIC_TEXT_DOMAIN ),
				'hierarchical'      => true,
				'rewrite'           => array( 'slug' => WIC_PLUGIN_PREFIX . '-styles' ),
				'capabilities'      => array(
					'assign_terms' => 'edit_posts',
					'edit_terms'   => 'edit_posts',
					'manage_terms' => 'edit_posts',
				)

			)
		);
		$this->setup_defaults();
	}
}