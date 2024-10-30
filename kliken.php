<?php

/**
Plugin Name: Kliken - Google Advertising and Stats
Plugin URI: http://kliken.com
Description: Kliken is a DIY online marketing platform. Start with FREE website analytics and SEO keyword ranking.
Version: 1.6.3
Author: Kliken
Author URI: http://kliken.com
Text Domain: kliken-all-in-one-marketing
Domain path: /languages
License: GPLv2 or later

@package Kliken - Google Advertising and Stats
 */

// This plugin uses PHP 5.3 features, so need to exit right away if the PHP version of the host is < 5.3.
define( 'KK_PHP_MIN_VERSION', '5.3.0' );
if ( version_compare( PHP_VERSION, KK_PHP_MIN_VERSION, '<' ) ) {
	if ( is_admin() ) {
		exit( esc_html__( 'This plugin requires PHP version 5.3 and later!' ) );
	}
}

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

define( 'KK_PLUGIN_SCHEMA_VERSION', '4' );
define( 'KK_PLUGIN_FILE', __FILE__ );
define( 'KK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'KK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'KK_NAMESPACE', 'Kliken\WpPlugin' );
define( 'KK_SETTING_PAGE', 'klikenconfig' );
define( 'KK_HOST', 'https://my.kliken.com/' );
define( 'KK_REST_API_URL', 'https://rest.sitewit.com/api/' );

define( 'KK_OPTION_NAME_AFFILIATE_ID', 'kk_affiliate_id' );
define( 'KK_OPTION_NAME_SCHEMA_VERSION', 'kk_schema_version' );
define( 'KK_OPTION_NAME_ACCOUNT_ID', 'kk_account_id' );
define( 'KK_OPTION_NAME_INVITATION_CODE', 'kk_invitation_code' );
define( 'KK_OPTION_NAME_API_TOKEN', 'kk_api_token' );

define( 'KK_AJAX_NONCE_NAME', 'kkAjaxNonce' );
define( 'KK_AJAX_NONCE_LINK_ACCOUNT', 'kk-link-account-nonce' );
define( 'KK_AJAX_NONCE_RESET_ACCOUNT', 'kk-reset-account-nonce' );

/**
 * Support for internationalization.
 */
function kk_load_textdomain() {
	load_plugin_textdomain( 'kliken-all-in-one-marketing', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

/**
 * This should contain all the checks to ensure the plugin will operate properly.
 */
function kk_activation_check() {
	// Check for cURL extension availability so we can connect to our API.
	if ( ! function_exists( 'curl_init' ) ) {
		wp_die( esc_html__( 'This plugin requires cURL PHP extension to be enabled. Please contact your hosting provider to enable it.', 'kliken-all-in-one-marketing' ) );
	}

	// Check if the site already has tracking code injected by cPanel.
	// Parsing the .htaccess file and find the inject code. Need test to make sure we can read the .htaccess file.
	$htaccess_file = get_home_path() . '.htaccess';
	if ( true === file_exists( $htaccess_file ) ) {
		$content = file_get_contents( $htaccess_file );
		if ( false !== strpos( $content, 'AddOutputFilterByType SUBSTITUTE text/html' )
			&& 1 === preg_match( '/sitewit.com\/v3\/\d+\/sw\.js/', $content ) ) {
			wp_die( esc_html__( 'This site seems to already have tracking code injected by cPanel. Please go to cPanel for SiteWit Reports.', 'kliken-all-in-one-marketing' ) );
		}
	}
}

/**
 * Run on plugin deactivation
 */
function kk_deactivation_check() {
	kk_plugin_db_cleanup( false );
}

/**
 * Run on plugin uninstallation
 */
function kk_uninstallation_check() {
	kk_plugin_db_cleanup( true );
}

/**
 * Clean up database used by the plugin
 *
 * @param boolean $uninstall Whether this is deactivation or uninstallation.
 * @return void
 */
function kk_plugin_db_cleanup( $uninstall = true ) {
	// Remove all database options so if the user install the plugin again, they will be starting fresh.
	delete_option( KK_OPTION_NAME_ACCOUNT_ID );
	delete_option( KK_OPTION_NAME_API_TOKEN );

	// Legacy. We don't store those anymore, and so do the consts. Those delete won't hurt though.
	delete_option( 'kk_user_token' );
	delete_option( 'kk_master_account' );
	delete_option( 'kk_tracking_script' );

	if ( true === $uninstall ) {
		delete_option( KK_OPTION_NAME_AFFILIATE_ID );
		delete_option( KK_OPTION_NAME_SCHEMA_VERSION );
		delete_option( KK_OPTION_NAME_INVITATION_CODE );
	}
}

add_action( 'plugins_loaded', 'kk_load_textdomain' );

register_activation_hook( __FILE__, 'kk_activation_check' );
register_deactivation_hook( __FILE__, 'kk_deactivation_check' );
register_uninstall_hook( __FILE__, 'kk_uninstallation_check' );

require_once 'init.php';
