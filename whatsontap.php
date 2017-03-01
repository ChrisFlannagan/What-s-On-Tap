<?php
/**
 * Plugin Name: What's On Tap?
 * Plugin URI: https://whoischris.com
 * Author: Chris Flannagan
 */

require 'includes/constants.php';
require 'vendor/autoload.php';

add_action( 'init', [ '\whatsontap\WhatsOnTap', 'init' ] );