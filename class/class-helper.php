<?php
/**
 * Helper class to provide some common functionalities
 *
 * @package Kliken - Google Advertising and Stats
 */

namespace Kliken\WpPlugin;

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

/**
 * Helper class
 */
class Helper {
	/**
	 * Determine if we have account id saved to database or not
	 *
	 * @return bool
	 */
	public static function no_account_id() {
		return ! get_option( KK_OPTION_NAME_ACCOUNT_ID );
	}

	/**
	 * Get account id saved to database
	 *
	 * @return mixed Integer for an account id if available. False otherwise.
	 */
	public static function get_account_id() {
		$id = trim( get_option( KK_OPTION_NAME_ACCOUNT_ID ) );

		return ( ! $id || ! ctype_digit( strval( $id ) ) ) ? false : $id;
	}

	/**
	 * Check if we have invitaion code and API token saved in database or not
	 *
	 * @return bool
	 */
	public static function for_invitation() {
		$code  = get_option( KK_OPTION_NAME_INVITATION_CODE );
		$token = get_option( KK_OPTION_NAME_API_TOKEN );

		return ( $code && $token );
	}

	/**
	 * Check if current page is setting page of the plugin
	 *
	 * @return boolean
	 */
	public static function is_setting_page() {
		global $pagenow;

		return ( 'admin.php' === $pagenow && isset( $_REQUEST['page'] ) && KK_SETTING_PAGE === $_REQUEST['page'] ); // WPCS: CSRF ok, input var ok.
	}

	/**
	 * Get setting page link
	 *
	 * @param boolean $absolute Whether to get absolute link or not.
	 * @return string
	 */
	public static function get_setting_page_link( $absolute = false ) {
		$link = 'admin.php?page=' . KK_SETTING_PAGE;

		if ( true === $absolute ) {
			$link = get_admin_url() . $link;
		}

		return $link;
	}

	/**
	 * Get Base64 string of SiteWit logo
	 * Convert SVG image here: https://www.base64-image.de
	 *
	 * @return string
	 */
	public static function get_base64_icon_sw() {
		return 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9InN3TG9nbyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgd2lkdGg9IjYwIiBoZWlnaHQ9IjYwIiB4PSIwcHgiIHk9IjBweCIJIHZpZXdCb3g9IjAgMCA2MCA2MCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNjAgNjA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQoJLnN0MHtmaWxsOiM1NTU1NTU7fQ0KPC9zdHlsZT4NCjxnPg0KCTxnPg0KCQk8cGF0aCBpZD0ibG9nb3BhdGgiIGNsYXNzPSJzdDAiIGQ9Ik0zNy42LDAuN2MtOC41LDAtMTUuOSw0LjctMTkuNywxMS43Yy0wLjctMC4xLTEuNC0wLjEtMi4xLTAuMUM3LjEsMTIuMywwLDE5LjQsMCwyOC4xDQoJCQlzNy4xLDE1LjgsMTUuOCwxNS44YzAuOCwwLDEuNS0wLjEsMi4zLTAuMmMtMC4xLDAuNi0wLjIsMS4xLTAuMiwxLjdjMCwwLjcsMC4xLDEuMywwLjMsMS45Yy0xLjcsMC43LTIuOSwyLjMtMi45LDQuMw0KCQkJYzAsMSwwLjMsMS45LDAuOCwyLjZjLTAuMy0wLjEtMC43LTAuMi0xLjEtMC4yYy0xLjUsMC0yLjcsMS4yLTIuNywyLjdzMS4yLDIuNywyLjcsMi43czIuNy0xLjIsMi43LTIuN2MwLTAuNC0wLjEtMC44LTAuMy0xLjINCgkJCWMwLjcsMC41LDEuNiwwLjcsMi41LDAuN2MyLjIsMCw0LjEtMS42LDQuNS0zLjZjMC4yLDAsMC41LDAsMC43LDBjMy45LDAsNy4xLTMuMiw3LjEtNy4xYzAtMC4yLDAtMC40LDAtMC42DQoJCQljMS44LDAuNSwzLjYsMC43LDUuNiwwLjdDNTAsNDUuNSw2MCwzNS41LDYwLDIzLjFTNTAsMC43LDM3LjYsMC43eiIvPg0KCTwvZz4NCjwvZz4NCjwvc3ZnPg0K';
	}

	/**
	 * Get Base64 string of Kliken logo
	 *
	 * @return string
	 */
	public static function get_base64_icon_kk() {
		return 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOS4yLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAzMDEuNiAyODUiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDMwMS42IDI4NTsiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4NCgkuc3Qwe2ZpbGwtcnVsZTpldmVub2RkO2NsaXAtcnVsZTpldmVub2RkO2ZpbGw6IzQxNDA0Mjt9DQo8L3N0eWxlPg0KPGc+DQoJPHBhdGggY2xhc3M9InN0MCIgZD0iTTIzNSwxNzEuMWMtNC43LDAuNC05LjIsMC4zLTEzLjUtMC40YzcuMS0zLjgsMTIuOS03LjksMTcuNS0xMi40YzEwLjQtMTAuMiwxMy45LTIxLjksMTEuOC0zMy43DQoJCWMtNi41LTEuMy0xMy0xLjEtMTkuNywwLjZjNi4xLTIuNywxMi41LTMuOCwxOS4xLTMuNGMtMC41LTEuOS0xLjEtMy45LTEuOS01LjhjLTcuMi0wLjEtMTMuNywxLjQtMTkuNSw0LjRjNC43LTQsMTAuNy02LjYsMTgtNy44DQoJCWMtMC45LTEuOC0xLjgtMy42LTIuOS01LjRjLTcuNi0xMi4zLTE4LjItMjAuMS0yOC42LTI5LjVjLTIuMy0yLjEtNC43LTQuMy02LjYtNi44YzAuNS0wLjksMC44LTIsMC45LTNjMS43LTAuMSwzLjIsMC42LDQuMSwyLjENCgkJYzAuMSwwLjIsMC4yLDAuNCwwLjMsMC42YzMuOCw5LjMsMTIuNSw1LjQsMTAuNy0yLjdjLTEuNi03LjItOS4yLTExLjgtMTYuMS0xMi41Yy0xNS43LTEuNy0yMCwxNi42LTExLjYsMjcuNw0KCQljOS41LDEyLjUsMzIuMywyMi4zLDI1LjEsNDMuMWMtMi4xLDYtNy45LDExLTE0LjIsMTEuOGMyLjYsMTguNywxLjYsMjYuNy0xMC4zLDM5LjFjMTAtMTIuMSw0LjUtMTgsNC43LTMxLjENCgkJYy05LDI4LjgtMjkuNiw0NC4zLTgxLjgsNDMuOGwtMC4xLDBjMjAuMyw5LjgsNDYuNCw4LjIsNjUuNCwxLjhjLTMxLjEsMTMuMy01Ni41LDE1LjItNzYuMiw1LjdDOTksMjA2LjIsOTYsMjE0LjgsODksMjI0LjgNCgkJYy00LjEsNS45LTkuOSwxMC40LTE4LjgsMTIuNWMtNS4zLDEuMy0xMi4xLTAuNC0xNy0yLjZjLTUuOS0yLjYtMTMuNi04LjEtMTQuMS0xNS4yYy0wLjItMi41LDAuOS01LjEsMi43LTYuOQ0KCQljMC4yLTAuMiwwLjUtMC41LDAuOC0wLjZjMS42LDEuMSwyLjEsMi41LDMuNywzLjNjMy42LDEuNyw4LjQtMiw5LjMtNS41YzEuMi00LjYtMi41LTguNC02LjQtMTAuMWMtOC43LTQtMjAuOCwxLTI1LjMsOS4xDQoJCWMtNC40LDcuOS0yLjgsMTcuNywwLjcsMjUuNmM3LjMsMTYuNCwyMy41LDI4LjcsNDEuMiwzMi4zYzMuMi04LjEsMy45LTE3LjEsMi4xLTI3YzMuMyw4LjcsMy45LDE3LjksMS43LDI3LjYNCgkJYzIuMSwwLjMsNC4yLDAuNCw2LjQsMC40YzEuNy05LjEsMS0xOC4yLTItMjcuMmM0LjUsOC40LDYuNCwxNy41LDUuOSwyNy4xYzEuMS0wLjEsMi4xLTAuMiwzLjItMC4zYzEuMy0wLjIsMi43LTAuNCw0LTAuNw0KCQljMC4yLTExLjMtMi4yLTIxLjEtNy4zLTI5LjNjNi43LDYuNiwxMC44LDE2LDEyLjQsMjhjMjIuMS02LjgsMzkuOC0yNi42LDQ5LjktNDYuN2MxLjIsMjUuNSwxOC44LDM5LjEsNDMuNiw0MA0KCQljNSwwLjIsMTAtMC40LDE0LjktMC45YzQuOC0wLjUsMTAuNi0xLjMsMTUsMS4yYzQuMSwyLjQsMC4yLDcuMS0yLjYsOC45Yy0wLjYsMC40LTEuMiwwLjctMS44LDAuOWMtMywxLjUtNS45LDMuOC03LDcNCgkJYy0yLjMsNi43LDQuMSwxMS40LDEwLjYsOC4yYzEwLjctNS4yLDE2LjktMTQuNywxOC41LTI2LjNjMi45LTIxLjItMTguOS0yOS43LTM2LjUtMzAuOGMtNy0wLjUtMTEuOS0wLjgtMTEtOS4zDQoJCWMxLTkuNyw3LjEtMTEuNiw1LjgtMjEuNWMxLjQsMy4yLDEuOCw1LjIsMS4xLDExLjVjMjguOCw4LjQsNTMsMS4zLDY3LjYtMjYuNmM1LjUtMTAuNSw2LjYtNi4yLDEwLjUtMi4xYzUuNSw1LjcsMTUsNS44LDIxLjQsMS42DQoJCWM0LjktMy4yLDguNS04LjgsOS4yLTE0LjZjMC40LTMuMSwwLTYuNC0xLjgtOWMtMy42LTUtMTIuMy0zLjctMTUuMywxLjRjLTEuMywyLjItMC45LDUuMiwxLjMsNi43YzEuOCwxLjMsMy45LDAuNyw1LjYtMC41DQoJCWMwLDAuNC0wLjEsMC45LTAuMSwxLjJjLTAuNiwzLjktNS4xLDYuNS04LjksNS4zYy01LjctMS45LTguMS0yNS4xLTI5LjgtNy42QzI0OC4zLDE2Ni41LDI0My41LDE3MC4xLDIzNSwxNzEuMUwyMzUsMTcxLjF6DQoJCSBNOTIuOCwxODUuMkw5Mi44LDE4NS4yYzQuOSw1LDEwLjMsOSwxNi4zLDExLjlsMC4yLTAuMWMtOC44LDUuOC0xNi4xLDEyLjktMjIsMjEuMWMtMTAuNi0xLjUtMjAuNC02LjctMjcuOS0xNC44DQoJCWMtMTMuNi0xNC43LTE3LjMtMzUuNS0xNi4yLTU0LjhjMC4zLTUuMS0wLjctMTAuNy0zLjYtMTVjLTEuNS0yLjItNC40LTUuMS03LjMtNC40Yy00LjUsMS4yLTMuNSw4LjQtMy42LDExLjYNCgkJYy0wLjIsNC44LTIuNCw5LjYtNi41LDEyLjNjLTIuOCwxLjgtNiwyLjQtOS4yLDEuOWMtNC45LTAuOC0xMC4xLTQuOS0xMi4xLTkuNWMtMS40LTMuMy0xLjUtOC41LDIuOS05LjNjMi4yLTAuNCw0LjIsMSw1LjEsMi45DQoJCWMwLjksMS45LDEsMy44LDMsNWMwLjcsMC40LDEuNiwwLjYsMi40LDAuNGMyLjEtMC42LDIuMS0zLjUsMS45LTUuMmMtMC4yLTEuNC0wLjYtMi43LTEtNC4xYy0yLjYtOC41LTAuNy0xNS4zLDYuOC0yMC40DQoJCWM0LjctMy4yLDEwLTQuNywxNS43LTQuNGMxNy40LDEuMSwzMS4zLDE4LjYsMzIuOCwzNS4yYzAuOCw4LjQsMC4zLDE4LjYsMy42LDI2LjNjMi4zLDUuMyw2LjUsMTAuOCwxMi4yLDEyLjYNCgkJQzg4LjYsMTg1LjQsOTAuOCwxODUuNSw5Mi44LDE4NS4yeiIvPg0KCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0xNzYuMiw3MGMwLjYtMC4xLDEuMy0wLjEsMS45LDBjNi40LDAuNywxMCw3LjUsMTEuNywxMi45YzEuMyw0LjMsMS45LDguOCwxLjgsMTMuM2MtMC4xLDMuNC0wLjYsNi44LTEuNiwxMA0KCQljLTEuOCw1LjctNS4yLDEwLjUtOS40LDE0LjZsMCwwYzMuMSwxMS43LDguNSwyMy4xLDE1LjUsMzIuOWMtMC41LTEuMS0wLjktMi4zLTEuNC0zLjVjLTEuNi00LjItMy4xLTguNS00LjUtMTIuOA0KCQljLTEtMy4yLTItNi40LTIuOC05LjZjLTAuMS0wLjMsMC0wLjUsMC4yLTAuN2MwLjItMC4yLDAuNS0wLjMsMC43LTAuMmMxLjgsMC41LDMuNiwwLjgsNS41LDAuOGMwLjEsMCwwLjIsMCwwLjMsMA0KCQljMS4zLDAuNSwyLjUsMS4xLDMuNiwxLjljNS43LDQuMSw4LjMsMTEuNSw5LjEsMTguMmMxLjEsOC41LTAuMiwxNy43LTIuOCwyNS44Yy0zLDkuMy04LjIsMTguNC0xNS43LDI0LjcNCgkJYy0wLjEsMC4xLTAuMiwwLjEtMC4zLDAuMWMtMC44LDAuMi0xLjUsMC40LTIuMywwLjZjLTAuMSwwLTAuMiwwLjEtMC4zLDAuMWMtNS44LDEuNS0xMS42LDIuNy0xNy42LDMuNGMtNi42LDAuOC0xMy4yLDEuMi0xOS44LDENCgkJYy0xMy40LTAuNC0yOC0zLjQtMzguNy0xMS44Yy01LjQtNC4yLTkuNS05LjYtMTIuMS0xNS45Yy0wLjctMS43LDAuMy0zLjIsMS4yLTQuNmMwLjMtMC41LDAuNy0xLDEtMS41YzUuNC05LjIsOS43LTE5LjYsMTAuMS0zMC4zDQoJCWMwLTAuNCwwLjMtMC43LDAuNy0wLjdjNS0wLjYsMTAtMS42LDE0LjctMy4yYy0xOS4zLDIuMS0zNi42LTYuMy00Mi4yLTI1LjljLTYtMjEuMi00Mi4xLTMzLjUtNDUuOS01Ny4zYy0xLTYuMSwwLjQtMTIsMy43LTE3LjINCgkJYzUuNS04LjksMTMuMi0xNi43LDIxLjktMjIuN2MwLjEtMC4xLDAuMy0wLjEsMC41LTAuMWw5LjYsMC40YzcuNS00LjQsMTUuNC03LDI0LThsMCwwYy03LjgsMC4zLTE4LDIuOC0yNSw2LjENCgkJYy0wLjEsMC4xLTAuMiwwLjEtMC40LDAuMWwtOC41LTAuNmMtOCw0LjUtMTIuNywxMC44LTE4LjcsMTcuNGMtMC42LDAuNi0xLjEsMS4zLTEuNywxLjljLTAuMywwLjMtMC43LDAuMy0xLDAuMQ0KCQljLTAuMy0wLjItMC40LTAuNi0wLjMtMWMxNS0zMi4zLDU4LjQtMzQuOCw4Ny0yMC4xYzAuMywwLjIsMC41LDAuNSwwLjQsMC45Yy0wLjEsMC40LTAuNSwwLjYtMC44LDAuNWMtMS4yLTAuMi0yLjQtMC4zLTMuNi0wLjMNCgkJYy0xLjktMC4xLTMuOS0wLjEtNS44LDBsLTEuMiwzLjFjLTAuMSwwLjMtMC4zLDAuNC0wLjYsMC41Yy02LjMsMC43LTEyLjMsMi45LTE3LjksNS43Yy0wLjEsMC4xLTAuMywwLjEtMC40LDAuMUw4Ny4yLDE4DQoJCWMtMTIuOCw1LjMtMjYuNywxOC43LTMxLjcsMzEuOGM3LTExLjYsMjAuNy0yMy4zLDMyLjYtMjkuMmMwLjEtMC4xLDAuMy0wLjEsMC40LTAuMWwxMSwxLjNjNi4zLTIuNywxMi44LTQuNCwxOS42LTUuMWwxLjEtMi43DQoJCWMwLjEtMC4zLDAuNC0wLjUsMC43LTAuNWMxNC42LDAuNywyNS40LDMuNywzNC4xLDE2YzAuMiwwLjMsMC4yLDAuNiwwLDAuOWMtMC4yLDAuMy0wLjUsMC40LTAuOCwwLjNjLTQuMi0xLjItOC0xLjktMTIuNS0yDQoJCWwtMS4xLDIuOGMtMC4xLDAuMy0wLjMsMC41LTAuNiwwLjVjLTYuMSwwLjgtMTMuOCwyLjYtMTkuNSw1LjFjLTAuMSwwLjEtMC4yLDAuMS0wLjQsMC4xTDEwOC4xLDM2Yy0xMy45LDYuNC0zMi42LDE5LjYtMzYuMywzNC45DQoJCWwwLDBjNi40LTEzLDIzLjYtMjUuMywzNi45LTMxLjFjMC4xLTAuMSwwLjMtMC4xLDAuNC0wLjFsMTIuMSwxLjljNy0yLjYsMTQuMy00LjMsMjEuNy00LjhsMS40LTNjMC4xLTAuMywwLjQtMC41LDAuNy0wLjQNCgkJYzguMywwLjcsMTYuMywyLjgsMjMuOCw2LjNjMC4xLDAuMSwwLjIsMC4xLDAuMywwLjNjNiw4LjUsMTEsMTguMywxMy4yLDI4LjZjMC4xLDAuMiwwLDAuNS0wLjIsMC43Yy0wLjIsMC4yLTAuNCwwLjMtMC42LDAuMg0KCQlDMTc5LjcsNjkuMiwxNzcuOSw2OS41LDE3Ni4yLDcwTDE3Ni4yLDcweiBNMTgyLjgsOTEuNWMwLTAuMy0wLjItMC42LTAuNS0wLjZjLTAuMywwLTAuNSwwLjItMC41LDAuNWMtMC44LDUuOS0zLjYsMTMuMy03LjMsMTcuMQ0KCQljLTAuMSwwLjEtMC4yLDAuMy0wLjIsMC41YzAsMC4yLDAuMSwwLjQsMC4yLDAuNWMwLjksMC44LDIuMSwxLjQsMy4yLDEuMmMyLjItMC40LDMuNS0zLDQuMi01LjNDMTgzLDEwMS4yLDE4My4xLDk1LjksMTgyLjgsOTEuNQ0KCQlMMTgyLjgsOTEuNXogTTExMy40LDEwOS4xYy0wLjItMC4yLTAuNi0wLjItMC44LDBjLTAuMiwwLjItMC4zLDAuNS0wLjIsMC44YzYuNywxNC43LDE2LjcsMjAsMzAuNiw5LjljMC4yLTAuMiwwLjMtMC40LDAuMi0wLjcNCgkJYy0wLjEtMC4zLTAuMy0wLjUtMC42LTAuNUMxMzMuOCwxMTguMiwxMjAuOCwxMTQuMSwxMTMuNCwxMDkuMUwxMTMuNCwxMDkuMXogTTExMiwxNzguNWMwLjQsMCwwLjgtMC4yLDEuMi0wLjZsLTQuMy02LjgNCgkJYy0wLjMsMC44LTAuNCwxLjktMC4zLDNDMTA4LjgsMTc2LjYsMTEwLjQsMTc4LjYsMTEyLDE3OC41TDExMiwxNzguNXogTTExMy43LDE3Ny4xYzAuNS0wLjksMC43LTIuMiwwLjUtMy41DQoJCWMtMC4zLTIuNi0xLjgtNC41LTMuNC00LjRjLTAuNiwwLjEtMS4xLDAuNC0xLjUsMC45TDExMy43LDE3Ny4xTDExMy43LDE3Ny4xeiBNMTE1LjgsMTczLjZjLTAuMS0yLjctMS40LTYuNS00LjMtNy4xDQoJCWMtMC4zLTAuMS0wLjUtMC4xLTAuOC0wLjFjLTAuNiwwLTEuMiwwLjItMS43LDAuNWMtMSwwLjYtMS44LDEuNi0yLjMsMi43Yy0wLjYsMS4zLTAuOCwyLjgtMC44LDQuM2MwLDEuNiwwLjUsMy4zLDEuMyw0LjcNCgkJYzAuNywxLjEsMS43LDIuMSwzLDIuNGMwLjMsMC4xLDAuNSwwLjEsMC44LDAuMWMwLjUsMCwxLTAuMiwxLjUtMC40YzAuOS0wLjUsMS43LTEuMywyLjItMi4yQzExNS41LDE3NywxMTUuOCwxNzUuMywxMTUuOCwxNzMuNg0KCQlMMTE1LjgsMTczLjZ6IE01NS42LDYyLjljLTAuMS0wLjEtMC4xLTAuMS0wLjIsMGMtMC4xLDAtMC4xLDAuMS0wLjEsMC4ydjEuNmMwLDAuMSwwLDAuMSwwLjEsMC4ybDguOCw3LjdjMC4xLDAuMSwwLjEsMC4xLDAuMiwwDQoJCWMwLjEsMCwwLjEtMC4xLDAuMS0wLjJ2LTEuNmMwLTAuMSwwLTAuMS0wLjEtMC4yTDU1LjYsNjIuOUw1NS42LDYyLjl6IE01NS42LDcwLjljLTAuMS0wLjEtMC4xLTAuMS0wLjIsMGMtMC4xLDAtMC4xLDAuMS0wLjEsMC4yDQoJCXYxLjZjMCwwLjEsMCwwLjEsMC4xLDAuMmw4LjgsNy43YzAuMSwwLjEsMC4xLDAuMSwwLjIsMGMwLjEsMCwwLjEtMC4xLDAuMS0wLjJ2LTEuNmMwLTAuMSwwLTAuMS0wLjEtMC4yTDU1LjYsNzAuOUw1NS42LDcwLjl6DQoJCSBNNTUuNiw2Ny4zYy0wLjEtMC4xLTAuMS0wLjEtMC4yLDBjLTAuMSwwLTAuMSwwLjEtMC4xLDAuMlY2OWMwLDAuMSwwLDAuMSwwLjEsMC4ybDguOCw3LjdjMC4xLDAuMSwwLjEsMC4xLDAuMiwwDQoJCWMwLjEsMCwwLjEtMC4xLDAuMS0wLjJ2LTEuNmMwLTAuMSwwLTAuMS0wLjEtMC4yTDU1LjYsNjcuM3oiLz4NCgk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNNTEuNiw5OS40QzUwLDk1LDQ5LjQsODkuOSw0OS45LDg0YzEuNCw4LjYsMy45LDE1LjYsNy41LDIxYzEuMywxLjEsMi43LDIuMSw0LjEsMy4xDQoJCWMtMC43LTMuMy0wLjctNy41LTAuMi0xMi42YzAuOCw2LjEsMiwxMSwzLjYsMTQuOGM0LjIsMi42LDguNyw1LDEzLjQsNy41YzAtMjMuNi0yMS40LTI3LjQtMzQuMS00NC42DQoJCUM0My4yLDg0LjcsNDYuMiw5Mi45LDUxLjYsOTkuNHoiLz4NCjwvZz4NCjwvc3ZnPg0K';
	}

	/**
	 * Get a list of supported languages (WP style) with a map to .NET style
	 *
	 * @return array
	 */
	public static function get_supported_languages() {
		return array(
			'en_US' => 'en',
			'es_ES' => 'es',
			'de_DE' => 'de',
			'de_CH' => 'de-CH',
			'fr_FR' => 'fr',
			'nl_NL' => 'nl',
		);
	}

	/**
	 * Get locale of WordPress site
	 * Default to English if not supported by the plugin
	 *
	 * @return string
	 */
	public static function get_locale() {
		if ( function_exists( 'get_user_locale' ) ) {
			$wp_locale = get_user_locale();
		} else {
			$wp_locale = get_locale();
		}

		$supported_langs = self::get_supported_languages();

		if ( array_key_exists( $wp_locale, $supported_langs ) ) {
			return $supported_langs[ $wp_locale ];
		}

		return 'en';
	}

	/**
	 * Setup plugin data by saving Account Id (got back from API) to database.
	 * Also remove API token from database as we no longer need it.
	 *
	 * @param int $account_id Account Id.
	 * @return void
	 */
	public static function setup_plugin_data( $account_id ) {
		// Save the account number into the database.
		update_option( KK_OPTION_NAME_ACCOUNT_ID, $account_id );

		// Done. We don't need account/api token anymore.
		delete_option( KK_OPTION_NAME_API_TOKEN );
	}
}
