<?php
/**
 * Plugin main class
 *
 * @package Kliken - Google Advertising and Stats
 */

namespace Kliken\WpPlugin;

use Kliken\WpPlugin\Exception\Api_Exception;
use Kliken\WpPlugin\Helper;

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

/**
 * Plugin main class
 */
class Plugin {
	/**
	 * Static instance of the class
	 *
	 * @var Plugin $instance
	 */
	private static $instance = null;

	/**
	 * Get a singleton class instance
	 *
	 * @return Plugin
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new Plugin();
		}

		return self::$instance;
	}

	/**
	 * A starting point to the plugin initialization.
	 * - Add hooks to create
	 *   + Config/Settings menu
	 *   + Print the tracking code onto the footer
	 */
	public function init_hooks() {
		// Some sort of housekeeping before doing anything else.
		add_action( 'plugins_loaded', array( $this, 'house_keeping' ) );

		// Check if plugin's database schema needs update.
		add_action( 'plugins_loaded', array( $this, 'update_schema' ) );

		// Add a settings menu to settings/config page of the plugin.
		add_action( 'admin_menu', function() {
			add_menu_page(
				__( 'Kliken - Google Advertising and Stats', 'kliken-all-in-one-marketing' ),       // Page title.
				__( 'Kliken Marketing', 'kliken-all-in-one-marketing' ), // Menu title.
				'manage_options',
				KK_SETTING_PAGE,
				array( '\Kliken\WpPlugin\Plugin', 'config_page' ),
				Helper::get_base64_icon_kk(),
				'99.999'
			);
		} );

		// Load assets for admin setting page(s).
		if ( Helper::is_setting_page() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_assets' ) );
		}

		// Tell user to continue with setting up the plugin.
		if ( Helper::no_account_id() && ! Helper::is_setting_page() ) {
			add_action( 'admin_notices', function() {
				// Show notice only if the current user has "manage_options" capability.
				if ( current_user_can( 'manage_options' ) ) {
					$message = sprintf(
						wp_kses(
							/* translators: %s: A hyperlink */
							__( '<strong>Kliken - Google Advertising and Stats plugin is almost ready.</strong> You must <a href="%s">link your Kliken account</a> in order for it to work.', 'kliken-all-in-one-marketing' ),
							array(
								'strong' => array(),
								'a'      => array( 'href' => array() ),
							)
						), esc_url( Helper::get_setting_page_link() )
					);

					Messages::show_warning( $message );
				}
			} );
		}

		// Add a handler to create, or reset account link via AJAX.
		if ( is_admin() ) {
			add_action( 'wp_ajax_kk_link_account', array( $this, 'ajax_link_account' ) );
			add_action( 'wp_ajax_kk_reset_account', array( $this, 'ajax_reset_account' ) );
		}

		// Add tracking code to front-end footer if available.
		$account_id = Helper::get_account_id();
		if ( false !== $account_id ) {
			add_action( 'wp_footer', function() use ( $account_id ) {
				?>
<script type="text/javascript">
	var _swaMa=["<?php echo esc_html( $account_id ); ?>"];"undefined"==typeof sw&&!function(e,s,a){function t(){for(;o[0]&&"loaded"==o[0][d];)i=o.shift(),i[w]=!c.parentNode.insertBefore(i,c)}for(var r,n,i,o=[],c=e.scripts[0],w="onreadystatechange",d="readyState";r=a.shift();)n=e.createElement(s),"async"in c?(n.async=!1,e.head.appendChild(n)):c[d]?(o.push(n),n[w]=t):e.write("<"+s+' src="'+r+'" defer></'+s+">"),n.src=r}(document,"script",["//analytics.sitewit.com/v3/"+_swaMa[0]+"/sw.js"]);
</script>
				<?php
			} );
		}
	}

	/**
	 * Render the configuration/setting page.
	 * - The config page will include the process to smoothly sign the user(s) up with a Kliken account.
	 * - When the account is setup properly, it shows the user with a set of icons to quickly navigate to Kliken reports.
	 */
	public static function config_page() {
		// Get Plugin instance because this function might get called from closure and not really have access to $this.
		$inc = \Kliken\WpPlugin\Plugin::get_instance();

		$setup_finished = false;
		$args           = array();

		// Include the correct view files.
		if ( Helper::no_account_id() ) {
			// If has invitation code, try to get authentication info from it.
			if ( Helper::for_invitation() ) {
				// Do API call.
				$account_info = Api::get_account_from_invitation(
					get_option( KK_OPTION_NAME_API_TOKEN ),
					get_option( KK_OPTION_NAME_INVITATION_CODE )
				);

				// Success.
				if ( isset( $account_info['AccountId'] ) && null !== $account_info['AccountId'] ) {
					Helper::setup_plugin_data( $account_info['AccountId'] );

					$setup_finished = true;
					$args           = array( 'reload' => true );
				}
			}
		} else {
			$setup_finished = true;
		}

		if ( true === $setup_finished ) {
			$inc->view( 'settings', $args );
		} else {
			$current_user = wp_get_current_user();

			$args = array(
				'kk_signup_url' => KK_HOST . 'auth/newaccount-wpkliken.aspx?lc=' . Helper::get_locale()
					. '&aff=' . rawurlencode( strtolower( get_option( KK_OPTION_NAME_AFFILIATE_ID, '' ) ) )
					. '&u=' . rawurlencode( home_url() )                     // site url.
					. '&n=' . rawurlencode( $current_user->display_name )    // name.
					. '&e=' . rawurlencode( $current_user->user_email ),      // email.
			);

			$inc->view( 'config', $args );
		}
	}

	/**
	 * Load a view
	 *
	 * @param string $name Name of the view to be loaded. The file must locates in "views" folder.
	 * @param array  $data Data to be passed to the view.
	 */
	public function view( $name, array $data = array() ) {
		$file = KK_PLUGIN_DIR . "views/{$name}.php";
		if ( true === file_exists( $file ) ) {
			// Include the template file.
			include $file;
		}
	}

	/**
	 * Load some common assets for (only) admin page
	 */
	public function load_admin_assets() {
		wp_enqueue_style( 'kk-style', KK_PLUGIN_URL . 'assets/style.css' );

		wp_enqueue_script( 'jquery-core' ); // In case there's no jQuery loaded yet.
	}

	/**
	 * Perform link account upon receiving an AJAX request. CSRF protection enabled!
	 */
	public function ajax_link_account() {
		// Security check.
		check_ajax_referer( KK_AJAX_NONCE_LINK_ACCOUNT, KK_AJAX_NONCE_NAME );

		$api_token  = isset( $_POST['apiToken'] ) ? sanitize_text_field( wp_unslash( $_POST['apiToken'] ) ) : ''; // WPCS: input var ok.
		$user_token = isset( $_POST['userToken'] ) ? sanitize_text_field( wp_unslash( $_POST['userToken'] ) ) : ''; // WPCS: input var ok.

		$error = '';

		// Check if the tokens are working by making request to REST API.
		if ( $api_token && $user_token ) {
			try {
				$account_info = Api::get_account( $api_token, $user_token );

				if ( isset( $account_info['accountNumber'] ) ) {
					Helper::setup_plugin_data( $account_info['accountNumber'] );
				}
			} catch ( Api_Exception $e ) {
				/* translators: %s: A message to display to user */
				$error = sprintf( __( 'API call unsuccessful! %s', 'kliken-all-in-one-marketing' ), $e->getMessage() );
			} catch ( \Exception $e ) {
				/* translators: %s: A message to display to user */
				$error = sprintf( __( 'Caught exception: %s', 'kliken-all-in-one-marketing' ), $e->getMessage() );
			}
		}

		echo wp_json_encode( array( 'error' => $error ) );

		wp_die(); // For ajax to return immediately with the JSON.
	}

	/**
	 * Reset data so that the user can link account again
	 */
	public function ajax_reset_account() {
		// Security check.
		check_ajax_referer( KK_AJAX_NONCE_RESET_ACCOUNT, KK_AJAX_NONCE_NAME );

		// Check passed, so just remove database-stored value.
		kk_plugin_db_cleanup( false );

		echo wp_json_encode( array( 'success' => true ) );

		wp_die();
	}

	/**
	 * House keeping function to make sure plugin has all the data it needs
	 */
	public function house_keeping() {
		// Set config information if available.
		if ( ! Helper::no_account_id() && file_exists( KK_PLUGIN_DIR . 'config.php' ) ) {
			$kk_affiliate_id    = '';
			$kk_invitation_code = '';
			$kk_account_token   = '';

			include KK_PLUGIN_DIR . 'config.php';

			if ( $kk_affiliate_id ) {
				update_option( KK_OPTION_NAME_AFFILIATE_ID, $kk_affiliate_id );
			}

			if ( $kk_invitation_code ) {
				update_option( KK_OPTION_NAME_INVITATION_CODE, $kk_invitation_code );
			}

			if ( $kk_account_token ) {
				update_option( KK_OPTION_NAME_API_TOKEN, $kk_account_token );
			}
		}

		// Set database schema version if there is not one yet.
		if ( false === get_option( KK_OPTION_NAME_SCHEMA_VERSION ) ) {
			add_option( KK_OPTION_NAME_SCHEMA_VERSION, KK_PLUGIN_SCHEMA_VERSION );
		}
	}

	/**
	 * Try to update the plugin to latest database schema version
	 */
	public function update_schema() {
		if ( ! is_admin() || ! defined( 'KK_PLUGIN_SCHEMA_VERSION' ) ) {
			return;
		}

		// Get current schema version. Default to 1. We might not have this before.
		$current_schema_version = get_option( KK_OPTION_NAME_SCHEMA_VERSION, '1' );

		if ( version_compare( $current_schema_version, KK_PLUGIN_SCHEMA_VERSION, '<' ) ) {
			$updater = new Updater();

			try {
				for ( $v = $current_schema_version; $v < KK_PLUGIN_SCHEMA_VERSION; $v++ ) {
					// Check if the updater function exists. In any loop, if it's not, break immediately!
					$method_name = "update_from{$v}_to" . ( $v + 1 );
					if ( ! method_exists( $updater, $method_name ) ) {
						break;
					}

					if ( $updater->{$method_name}() ) {
						// Update the current schema to the latest (code) version.
						update_option( KK_OPTION_NAME_SCHEMA_VERSION, $v + 1 );
					} else {
						// Updating failed for some reason. Break from the loop.
						break;
					}
				}
			} catch ( \Exception $ex ) {
				// Don't need to do anything here as it will also break the loop.
				return;
			}
		}
	}
}
