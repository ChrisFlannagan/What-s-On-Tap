<?php

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
		add_action( 'init', array( $this, 'create' ) );
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