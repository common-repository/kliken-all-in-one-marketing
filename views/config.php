<?php
/**
 * Config page
 *
 * @package Kliken - Google Advertising and Stats
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

?>

<div class="wrap kk-config-page" id="kk-config-page">
	<h2><?php esc_html_e( 'Get your Kliken account', 'kliken-all-in-one-marketing' ); ?></h2>
	<iframe id="kk-frame" scrolling="no" width="500" seamless="seamless" src="<?php echo esc_attr( $data['kk_signup_url'] ); ?>"></iframe>

	<div class="kk-link-account" id="kk-link-account" style="display: none;">
		<input type="hidden" id="api-token" name="apiToken" value="" />
		<input type="hidden" id="user-token" name="userToken" value="" />

		<div class="kk-center">
			<button class="button button-primary kk-button-big" id="link-account-btn" type="button">
				<?php esc_html_e( 'Link Your Account', 'kliken-all-in-one-marketing' ); ?>
			</button>
		</div>
	</div>

	<div class="kk-note kk-default-hide" id="kk-signup-info">
		<ul>
			<li><?php esc_html_e( 'We are using the information from currently logged in WordPress user to fill out the form. Please feel free to make changes.', 'kliken-all-in-one-marketing' ); ?></li>
			<li><?php esc_html_e( 'Please provide a secured password for your account', 'kliken-all-in-one-marketing' ); ?></li>
		</ul>
	</div>

	<div class="kk-note kk-default-hide" id="kk-login-info">
		<ul>
			<li>
			<?php
				printf(
					wp_kses(
						/* translators: 1: A hyperlink 2: Same hyperlink */
						__( 'If this is not the account you wanted, please navigate to <a href="%1$s">%2$s</a> to log out.', 'kliken-all-in-one-marketing' ),
						array( 'a' => array( 'href' => array() ) )
					), esc_url( KK_HOST ), esc_url( KK_HOST )
				);
			?>
			</li>
		</ul>
	</div>

	<div class="kk-note kk-default-hide" id="kk-form-info">
		<ul>
			<li><?php esc_html_e( 'This form is under secured SSL connection, so your information is safe with us.', 'kliken-all-in-one-marketing' ); ?></li>
		</ul>
	</div>
</div>

<script type="text/javascript">
	/* This code uses window.postMessage() for inter-window/domain messaging. It's not supported by IE < 8. */
	var kkHost = <?php echo wp_json_encode( KK_HOST ); ?>;  // The host to send the message to.
	var kkFrame = jQuery("#kk-frame");
	var linkButton = jQuery("#link-account-btn");
	var header = jQuery("#kk-config-page").find("h2");

	function sameOrigin(host1, host2) {
		// Generalize the domain, removing trailing slash(es)
		host1 = host1.replace(/\/+$/, "");
		host2 = host2.replace(/\/+$/, "");

		return host1 === host2;
	}

	function receiveTokens(event) {
		var oriEvent = event.originalEvent;

		// Don't do anything if the message not come from us
		if ( ! sameOrigin(oriEvent.origin, kkHost)) return;

		// Process the data received
		var data = jQuery.parseJSON(oriEvent.data);
		jQuery.each(data, function(i, item) {
			switch (item.mType) {
				case "wh": // window height
					// Set height of the iframe according to its content's actual height
					kkFrame.height(item.wHeight);

					// Show/hide some guidance information based on the type of window the iFrame loaded
					jQuery(".kk-note").hide();

					if (item.wName !== "link-account") {
						// Login page
						header.text("<?php esc_html_e( 'Link to your Kliken account', 'kliken-all-in-one-marketing' ); ?>")
						jQuery("#kk-form-info").show();
					} else {
						jQuery("#kk-login-info").show();

						// Enable the link account button
						linkButton.removeAttr("disabled");
					}

					if (item.wName === "new-account") {
						// Sign-up page
						header.text("<?php esc_html_e( 'Get your Kliken account', 'kliken-all-in-one-marketing' ); ?>");
						jQuery("#kk-signup-info").show();
					}

					break;

				case "tk": // tokens
					if (item.apiToken !== "" && item.userToken !== "") {
						// Show the link button
						jQuery("#kk-link-account").show();

						// Only one master account for this user, link account right away
						if (item.numAcct === 1) {
							linkAccount(item.apiToken, item.userToken);
						} else {
							// Save the tokens outside the frame
							jQuery("#api-token").val(item.apiToken);
							jQuery("#user-token").val(item.userToken);
						}
					}

					break;

				default:
					break;
			}
		});
	}

	function checkMessage() {
		document.getElementById("kk-frame").contentWindow.postMessage("ping", kkHost);
	}

	function linkAccount(apiToken, userToken) {
		// Disable the button so the user won't make multiple requests
		linkButton.attr("disabled", "disabled").text("<?php esc_html_e( 'Linking account...', 'kliken-all-in-one-marketing' ); ?>");

		// Prepare data to post
		var data = {
			action: "kk_link_account",
			<?php echo esc_attr( KK_AJAX_NONCE_NAME ); ?>: <?php echo wp_json_encode( wp_create_nonce( KK_AJAX_NONCE_LINK_ACCOUNT ) ); ?>,
			apiToken: apiToken,
			userToken: userToken
		};

		// Make ajax request, expecting JSON response. "ajaxurl" is a global JS variable from WordPress
		jQuery.post(ajaxurl, data, function(response) {
			if (response === -1 || response === null || response.error !== "") {
				linkButton.text("<?php esc_html_e( 'Linking Failed', 'kliken-all-in-one-marketing' ); ?>");
				alert("<?php esc_html_e( 'Request failed, please try again!', 'kliken-all-in-one-marketing' ); ?>");
			} else {
				linkButton.text("<?php esc_html_e( 'Success! Redirecting...', 'kliken-all-in-one-marketing' ); ?>");
			}
			location.reload();
		}, "json");
	}

	jQuery(document).ready(function() {
		jQuery(window).on("message", receiveTokens);

		kkFrame.on("load", function() { // everytime the iframe is loaded/reloaded
			checkMessage();
		});

		// Link account button clicked with an ajax request
		linkButton.on("click", function() {
			linkAccount(jQuery("#api-token").val(), jQuery("#user-token").val());
		});
	});
</script>
