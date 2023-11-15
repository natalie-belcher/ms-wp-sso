<?php

final class MSWPSSO_Login_Form {

	public final const POST_REQUEST = [
		'login_key'   => 'mswpsso_action',
		'login_value' => 'mswpsso_login',
		'login_nonce' => 'mswpsso_login_nnc',
	];

	public function __construct() {
		add_action( 'init', [ $this, 'post_request_listener' ] );
		$this->login_form_init();
	}

	public function login_form_init(): void {
		// Add 'Log in with Microsoft' SSO button to login page login form
		add_filter( 'login_message', [
			$this,
			'login_form_login_button',
		] );
	}

	public function login_form_login_button(): void {
		?>
		<form method="post" action="">
			<?php
			if ( function_exists( 'wp_nonce_field' ) ) {
				wp_nonce_field( self::POST_REQUEST['login_nonce'] );
				?>
				<input type="hidden"
					name="<?php echo self::POST_REQUEST['login_key']; ?>"
					value="<?php echo self::POST_REQUEST['login_value']; ?>">
				<svg xmlns="http://www.w3.org/2000/svg" height="2.4em"
					viewBox="0 0 448 512">
					<!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
					<style>svg {
							fill: #2271b1
						}</style>
					<path
						d="M0 32h214.6v214.6H0V32zm233.4 0H448v214.6H233.4V32zM0 265.4h214.6V480H0V265.4zm233.4 0H448V480H233.4V265.4z"/>
				</svg>
				<input class="button button-secondary button-large"
					type="submit" value="Log in with Microsoft"/>
				<?php
			}
			?>
		</form>
		<?php
	}

	public function post_request_listener(): void {
		if ( isset( $_POST[ self::POST_REQUEST['login_key'] ] ) ) {
			check_admin_referer( self::POST_REQUEST['login_nonce'] );
			$credentials = [
				'tenant'       => get_option( MSWPSSO_Init::OPTION_TENANT),
				'client_id'    => get_option( MSWPSSO_Init::OPTION_CLIENT_ID ),
				'redirect_url' => urlencode( MSWPSSO_REDIRECT_URI ),
				// This is sent back from Azure in the response
				'state'        => 12345,
				// Included in the ID Token returned
				'nonce'        => $this->set_nonce_with_cookie(),
			];

			$url = 'https://login.microsoftonline.com/' . $credentials['tenant'] . '/oauth2/v2.0/authorize?client_id=' . $credentials['client_id'] . '&response_type=id_token+token&redirect_uri=' . $credentials['redirect_url'] . '&response_mode=form_post&scope=openid+profile+email&state=' . $credentials['state'] . '&nonce=' . $credentials['nonce'] . '&domain_hint=informa&allowbacktocommon=True';
			header( "Location: $url" );
			die();
		}
	}

	private function set_nonce_with_cookie(): string {
		$cookie_value = time();
		MSWPSSO_Cookies::set( $cookie_value );
		return $cookie_value;
	}
}
