<?php
/**
 * Plugin Name: What's On Tap?
 * Plugin URI: https://whoischris.com
 * Author: Chris Flannagan
 */

require 'includes/constants.php';
require 'vendor/autoload.php';

$taps = new \whatsontap\post_types\Taps();
$taps = new \whatsontap\post_types\Tap_Lists();
$styles = new \whatsontap\taxonomies\Styles();