<?php

namespace whatsontap;

class Tap_List {
	
	public $taps = array();
	
	public function __construct( $tap_list_id = 0 ) {
		$this->load_list( $tap_list_id );
	}
	
	public function load_list( $tap_list_id ) {
		
	}
}