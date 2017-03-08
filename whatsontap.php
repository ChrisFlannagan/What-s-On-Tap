<?php
/**
 * Plugin Name: What's On Tap?
 * Plugin URI: https://whoischris.com
 * Author: Chris Flannagan
 * Version: 0.1
 *
 * Description: Display "What's on Tap" at a bar or restaurant.
 * 
 * This file is the main plugin file.  It loads our constants, autoloads our classes then inits everything else
 * through the WhatsOnTap static class.
 */

require_once( 'includes/constants.php' );
require_once( 'vendor/autoload.php' );

\whatsontap\OnTap::init();
add_action( 'wp_enqueue_scripts', function() { \whatsontap\OnTap::enqueue(); } );
add_action( 'admin_enqueue_scripts', function() { \whatsontap\OnTap::admin_enqueue(); } );