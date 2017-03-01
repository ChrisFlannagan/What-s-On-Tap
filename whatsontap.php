<?php
/**
 * Plugin Name: What's On Tap?
 * Plugin URI: https://whoischris.com
 * Author: Chris Flannagan
 * Version: 0.1
 *
 * Description: Display "What's on Tap" at a bar or restaurant.
 */

require 'includes/constants.php';
require 'vendor/autoload.php';

add_action( 'init', [ '\whatsontap\WhatsOnTap', 'init' ] );