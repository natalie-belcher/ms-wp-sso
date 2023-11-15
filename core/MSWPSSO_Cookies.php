<?php

final class MSWPSSO_Cookies {

	public final const COOKIE_NAME        = 'MSWPSSOAuthCookie';
	public final const DEFAULT_EXPIRATION = 180;

	public static function set( string $cookie_value, int $expiration_in_seconds = self::DEFAULT_EXPIRATION ): void {
		setcookie( self::COOKIE_NAME, $cookie_value, time() + $expiration_in_seconds );
	}

	public static function get(): string {
		$cookie_value = isset( $_COOKIE[ self::COOKIE_NAME ] ) ?? null;
		if ( ! $cookie_value ) {
			return false;
		}
		return (string) $cookie_value;
	}

	public static function validate_cookie( string $returned_cookie_value ): bool {
		$stored_cookie_value = self::get();
		if ( $returned_cookie_value === $stored_cookie_value ) {
			return true;
		}
		return false;
	}
}