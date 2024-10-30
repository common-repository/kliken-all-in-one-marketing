<?php
/**
 * Settings page
 *
 * @package Kliken - Google Advertising and Stats
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

?>

<?php if ( isset( $data['reload'] ) && true === $data['reload'] ) : ?>
	<script type="text/javascript">location.reload(true);</script>
<?php endif; ?>

<div class="wrap kk-config-page">
	<h2>
		<?php esc_html_e( 'Kliken Dashboard', 'kliken-all-in-one-marketing' ); ?>
	</h2>

	<div class="kk-report-shortcuts">
		<div class="shortcut banner" id="kk-link-newcamp"  style="background-image: url(<?php echo esc_attr( KK_PLUGIN_URL ) . 'assets/banner.png'; ?>);">
			<div class="banner-text container">
				<span class="banner-text heading"><?php esc_html_e( 'Unleash the marketing and grow your business', 'kliken-all-in-one-marketing' ); ?></span>
				<span class="banner-text sub-heading"><?php esc_html_e( 'More Visitors = More Leads = More Sales!', 'kliken-all-in-one-marketing' ); ?></span>
			</div>
		</div>

		<div class="shortcut shortcut-tile" id="kk-link-marketing">
			<span class="dashicons dashicons-welcome-widgets-menus"></span>
			<h1><?php esc_html_e( 'Marketing', 'kliken-all-in-one-marketing' ); ?></php></h1>
		</div>
		<div class="shortcut shortcut-tile" id="kk-link-leads">
			<span class="dashicons dashicons-groups"></span>
			<h1><?php esc_html_e( 'Leads', 'kliken-all-in-one-marketing' ); ?></php></h1>
		</div>
		<div class="shortcut shortcut-tile" id="kk-link-stats">
			<span class="dashicons dashicons-chart-line"></span>
			<h1><?php esc_html_e( 'Stats', 'kliken-all-in-one-marketing' ); ?></php></h1>
		</div>
	</div>

	<h3><?php esc_html_e( 'Change account', 'kliken-all-in-one-marketing' ); ?></h3>
	<div class="kk-message">
		<?php
			echo wp_kses(
				__( 'If you want to link this WordPress site to another Kliken account, please click <a id="reset-link" href="#">here</a>', 'kliken-all-in-one-marketing' ),
				array(
					'a' => array(
						'id'   => array(),
						'href' => array(),
					),
				)
			);
		?>
	</div>


	<h3><?php esc_html_e( 'Contact Us', 'kliken-all-in-one-marketing' ); ?></h3>
	<div class="kk-contact">
		<?php esc_html_e( 'Call us: 1-877-474-8394 (Monday to Friday: 9:00 AM - 6:00 PM EST)', 'kliken-all-in-one-marketing' ); ?><br/>
		<?php esc_html_e( 'Email: ', 'kliken-all-in-one-marketing' ); ?>
			<a href="mailto:support@kliken.com">support@kliken.com</a><br/>
		<?php
			printf(
				wp_kses(
					/* translators: %s: A hyperlink */
					__( 'Create a support <a target="_blank" rel="noopener noreferrer" href="%s">ticket</a>', 'kliken-all-in-one-marketing' ),
					array(
						'a' => array(
							'target' => array(),
							'rel'    => array(),
							'href'   => array(),
						),
					)
				), esc_url( 'http://support.sitewit.com/hc/en-us/requests/new' )
			);
		?>
		<br/>
		<?php esc_html_e( 'Or find us', 'kliken-all-in-one-marketing' ); ?>
			<a target="_blank" rel="noopener noreferrer" href="https://www.facebook.com/SiteWit"><span class="dashicons dashicons-facebook"></span></a>
			<a target="_blank" rel="noopener noreferrer" href="https://twitter.com/SiteWit"><span class="dashicons dashicons-twitter"></span></a>
			<a target="_blank" rel="noopener noreferrer" href="https://plus.google.com/115202446868642776828"><span class="dashicons dashicons-googleplus"></span></a>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery("#kk-banner").on("load", function() {
			jQuery(this).css("display", "block");
		});

		jQuery("#reset-link").on("click", function() {
			if (confirm("<?php esc_html_e( 'Are you sure you want to re-link your account? Current information will be lost!', 'kliken-all-in-one-marketing' ); ?>")) {
				// Request to clear associated data of the account being linked
				var data = {
					action: "kk_reset_account",
					<?php echo esc_attr( KK_AJAX_NONCE_NAME ); ?>: <?php echo wp_json_encode( wp_create_nonce( KK_AJAX_NONCE_RESET_ACCOUNT ) ); ?>
				};

				// Make ajax request, expecting JSON response. "ajaxurl" is a global JS variable from WordPress
				jQuery.post(ajaxurl, data, function (response) {
					if (response === -1 || response === null) {
						alert("<?php esc_html_e( 'Request failed, please try again!', 'kliken-all-in-one-marketing' ); ?>");
					} else {
						// Refresh the page (with no cache) and user will be presented with the config page
						location.reload(true);
					}
				}, "json");
			}
		});

		jQuery("div.shortcut").on("click", function() {
			var baseUrl = <?php echo wp_json_encode( KK_HOST ); ?>;

			var elId = jQuery(this).attr("id").split("-");
			switch(elId[2]) {
				case "newcamp":
					baseUrl += "smb/campaigns/new/Default.aspx?load=new";
					break;
				case "marketing":
					baseUrl += "smb/campaigns/new/Default.aspx?load=new";
					break;
				case "leads":
					baseUrl += "smb/connect/dashboard";
					break;
				case "stats":
					baseUrl += "smb/analytics";
					break;
			}

			var win = window.open(baseUrl, "_blank");
			win.opener = null;
			win.focus();
		});
	});
</script>
