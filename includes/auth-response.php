<?php
/**
 * Process the Azure AD SSO sign-in request and log in the user if successful.
 *
 * IMPORTANT: do not relocate this file as any change to the file path will
 * break the redirect_uri added to the app registered to the Microsoft Identity Platform
 *
 */

const WP_USE_THEMES = false;
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once $parse_uri[0] . 'wp-load.php';
require_once MSWPSSO_DIR . 'core/enum/MSWPSSO_PageType.php';
require_once MSWPSSO_DIR . 'core/MSWPSSO_Login_Handler.php';

new MSWPSSO_Login_Handler();
