<?php

readonly final class MSWPSSO_Menu {
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	public function admin_menu(): void {
		add_menu_page(
			'MS WP SSO',
			'MS WP SSO',
			'manage_options',
			'mswpsso',
			[ ( new MSWPSSO_Settings_Page ), 'render' ],
			'dashicons-id-alt',
		);
	}

	public function register_settings(): void {
		register_setting( MSWPSSO_Init::SETTINGS_GROUP, MSWPSSO_Init::OPTION_CLIENT_ID );
		register_setting( MSWPSSO_Init::SETTINGS_GROUP, MSWPSSO_Init::OPTION_CONTACT_EMAIL );
		register_setting( MSWPSSO_Init::SETTINGS_GROUP, MSWPSSO_Init::OPTION_ERROR_PAGE_ID );
		register_setting( MSWPSSO_Init::SETTINGS_GROUP, MSWPSSO_Init::OPTION_TENANT );
	}
}

