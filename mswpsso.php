<?php
/*
Plugin Name: MS Single Sign-on for WP
Description: A WordPress Plugin for Single Sign On (SSO) using Microsoft Active Directory accounts
Version: 1.0
Author: Natalie Belcher
*/

const MSWPSSO_VERSION = '1.0';
define( 'MSWPSSO_DIR', plugin_dir_path( __FILE__ ) );
define( 'MSWPSSO_REDIRECT_URI', plugin_dir_url( __FILE__ ) . 'includes/auth-response.php' );

require_once MSWPSSO_DIR . 'core/MSWPSSO_Menu.php';
require_once MSWPSSO_DIR . 'core/MSWPSSO_Settings_Page.php';
require_once MSWPSSO_DIR . 'core/MSWPSSO_Cookies.php';
require_once MSWPSSO_DIR . 'core/MSWPSSO_Login_Form.php';

final class MSWPSSO_Init {
	public final const OPTION_CLIENT_ID     = 'MSWPSSO_client_id';
	public final const OPTION_TENANT        = 'MSWPSSO_tenant';
	public final const OPTION_CONTACT_EMAIL = 'MSWPSSO_contact_email';
	public final const OPTION_ERROR_PAGE_ID = 'MSWPSSO_error_page_id';
	public final const SETTINGS_GROUP       = 'MSWPSSO-settings-group';

	public function __construct() {
		$this->setup();
		new MSWPSSO_Login_Form();
		new MSWPSSO_Menu();
	}

	public function setup(): void {
		register_activation_hook( __FILE__, [ $this, 'plugin_activation' ] );
		register_deactivation_hook( __FILE__, [
			$this,
			'plugin_deactivation',
		] );
		register_uninstall_hook( __FILE__, [ $this, 'plugin_uninstall' ] );
	}

	public function plugin_activation(): void {}

	public function plugin_deactivation() {

	}

	public function plugin_uninstall(): void {
		unregister_setting( self::SETTINGS_GROUP, self::OPTION_CLIENT_ID );
		unregister_setting( self::SETTINGS_GROUP, self::OPTION_TENANT );
		unregister_setting( self::SETTINGS_GROUP, self::OPTION_CONTACT_EMAIL );
		unregister_setting( self::SETTINGS_GROUP, self::OPTION_ERROR_PAGE_ID );
		delete_option( self::OPTION_CLIENT_ID );
		delete_option( self::OPTION_TENANT );
		delete_option( self::OPTION_CONTACT_EMAIL );
		delete_option( self::OPTION_ERROR_PAGE_ID );
	}
}

// Plugin init
new MSWPSSO_Init();