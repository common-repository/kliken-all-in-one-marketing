<?php
/**
 * Logger class
 *
 * @package Kliken - Google Advertising and Stats
 */

namespace Kliken\WpPlugin;

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

/**
 * Logger class.
 * Only available in WP_DEBUG mode, and if Monolog is loaded via Composer.
 */
class Logger {
	/**
	 * Static instance of the class
	 *
	 * @var Logger $logger
	 */
	public static $logger = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		if ( WP_DEBUG && class_exists( '\Monolog\Logger' ) ) {
			self::$logger = new \Monolog\Logger( 'Kliken WP Logger' );
			self::$logger->pushHandler( new \Monolog\Handler\BrowserConsoleHandler(), \Monolog\Logger::DEBUG );
		}
	}

	/**
	 * Log a message of a certain type
	 *
	 * @param string $type    Message type.
	 * @param string $message Message to be logged.
	 * @return void
	 */
	public static function log( $type, $message ) {
		if ( null === self::$logger ) {
			return;
		}

		self::$logger->addDebug( $type, (array) $message );
	}
}
