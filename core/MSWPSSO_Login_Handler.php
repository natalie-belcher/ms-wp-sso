<?php

final class MSWPSSO_Login_Handler {

	private array|WP_Error $auth_response;
	private ?string $user_email;
	private WP_User|bool $user;

	public function __construct() {
		$this->auth_response = $this->azure_auth();
		$this->user_email    = $this->response_handler( $this->auth_response );
		if ( $this->user_email ) {
			$this->user = $this->find_wp_user( $this->user_email );
			if ( $this->user instanceof WP_User ) {
				$this->login_request( $this->user );
				$this->redirect( PageType::Home );
			}
		} else {
			$this->redirect( PageType::Admin );
		}
	}

	public function azure_auth(): array|WP_Error {
		$access_token = htmlspecialchars( $_POST['access_token'] );
		$token_type   = htmlspecialchars( $_POST['token_type'] );

		$url  = 'https://graph.microsoft.com/oidc/userinfo';
		$args = [
			'headers' => [
				'Authorization' => $token_type . ' ' . $access_token,
			],
		];
		return wp_remote_post( $url, $args );
	}

	public function response_handler( array|WP_Error $response ): ?string {
		if ( is_array( $response ) && ! is_wp_error( $response ) && isset( $response['body'] ) ) {

			if ( isset ( $_POST['id_token'] ) ) {
				$encoded_id_token = $_POST['id_token'];
				// id_token is a JWT but w/o a third-party library, we can't "officially" JWT decode it
				// Find the nonce in the "decoded" string
				$decoded_id_token = base64_decode( $encoded_id_token );
				if ( $decoded_id_token ) {
					preg_match( '/"nonce":"(\d*)"/im', $decoded_id_token, $output_array );
					$returned_cookie_value = isset( $output_array[1] ) ?? null;
					if ( $returned_cookie_value ) {
						$validation = MSWPSSO_Cookies::validate_cookie( $returned_cookie_value );
						if ( $validation ) {
							$body = json_decode( $response['body'] ); // use the content
							return $body->email;
						}
					}
				}
			}
		}
		return null;
	}

	public function find_wp_user( string $user_email ): WP_User|bool {
		$user = get_user_by( 'email', $user_email );

		if ( ! $user ) {
			if ( get_permalink( get_option( MSWPSSO_Init::OPTION_ERROR_PAGE_ID ) ) ) {
					$this->redirect( PageType::Custom );
			} else {
					echo '<div class="azureadsso__login--error" style="border: 1px solid #c3c4c7;width: 50%;margin: 5rem auto; text-align: center">
						<h1 class="azureadsso__login--error-heading">User Account Not Found</h1>
						<h2 class="azureadsso__login--error-subhead">It appears you do not have an account registered on this site.</h2>
						<p class="azureadsso__login--error-message" style="font-size: 1.1em; font-weight: bold">To request an account, please contact: <a href="mailto:' . esc_attr( get_option( MSWPSSO_Init::OPTION_CONTACT_EMAIL ) ) . '">' . esc_html( get_option( MSWPSSO_Init::OPTION_CONTACT_EMAIL ) ) . '</a></p>
					</div>';
				}
				return false;
			}
		else {
			return $user;
		}
	}

	public function login_request( WP_User $user ): bool {
		wp_set_current_user( $user->ID, $user->user_login );
		wp_set_auth_cookie( $user->ID );
		do_action( 'wp_login', $user->user_login, $user );

		return true;
	}

	public function redirect( PageType $type ): never {
		$link = match ( $type ) {
			PageType::Admin  => site_url() . '/wp-admin',
			PageType::Custom => get_permalink( get_option( MSWPSSO_Init::OPTION_ERROR_PAGE_ID ) ),
			PageType::Home   => get_home_url(),
		};

		wp_redirect( $link );
		exit();
	}
}