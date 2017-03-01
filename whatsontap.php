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

require 'includes/constants.php';
require 'vendor/autoload.php';

add_action( 'init', [ '\whatsontap\WhatsOnTap', 'init' ] );