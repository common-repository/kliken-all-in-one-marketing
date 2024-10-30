<?php
/**
 * Initialize plugin functionalities
 *
 * @package Kliken - Google Advertising and Stats
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

require 'vendor/autoload.php';

// Register a logger.
new \Kliken\WpPlugin\Logger();

// Initialize plugin functionality.
$inc = \Kliken\WpPlugin\Plugin::get_instance();
$inc->init_hooks();
