<?php

final class MSWPSSO_Settings_Page {
	public function render(): void {
		?>
		<div class="wrap mswp">
			<div class="mswpsso--header">
				<h2> MS Single Sign-on for WP <span>v<?php echo MSWPSSO_VERSION; ?></span>
				</h2>
				<p>A WordPress Plugin for Single Sign-on using Microsoft
					Active Directory accounts</p>
			</div>
			<hr/>
			<h3>Setup Instructions</h3>
			<p>In order to set up this plugin, you will first need to register your App with the Microsoft Identity Platform.</p>
			<p>Detailed instructions on how to do this can be found on the <a href ="https://learn.microsoft.com/en-us/entra/identity-platform/quickstart-register-app">Microsoft Documentation Site</a></p>
			<p>You will need the redirect URI listed below to add to your app.</p>
			<table class="form-table">
				<tbody class="mswpsso__table--instructions">
				<tr>
					<th>Redirect URI to add to our Azure App:</th>
					<?php
					// The redirect URI for your app, where authentication responses can be sent and received
					// This URI must be registered in the Microsoft Identity Platform App portal
					// If not added to the Microsoft Identity Platform App, the endpoint will pick one registered redirect_uri at random to send the user back to.
					?>
					<td><?php echo MSWPSSO_REDIRECT_URI; ?></td>
				</tr>
				</tbody>
			</table>
			<hr/>
			<form action="options.php" method="post">
				<?php
				settings_fields( MSWPSSO_Init::SETTINGS_GROUP );
				do_settings_sections( MSWPSSO_Init::SETTINGS_GROUP );
				?>

				<h3>Settings</h3>
				<table class="form-table">
					<tbody class="mswpsso__table--settings">
					<tr>
						<th>
							<label
								for="<?php echo esc_attr( MSWPSSO_Init::OPTION_CLIENT_ID ); ?>">
								Client
								ID </label>
						</th>
						<td>
							<input
								class="text"
								type="text"
								size="50"
								id="<?php echo esc_attr( MSWPSSO_Init::OPTION_CLIENT_ID ); ?>"
								name="<?php echo esc_attr( MSWPSSO_Init::OPTION_CLIENT_ID ); ?>"
								value="<?php echo esc_attr( get_option( MSWPSSO_Init::OPTION_CLIENT_ID ) ); ?>"
								placeholder=""/>
							<p class="description">The Client ID provided by the
								Active Directory team</p>
						</td>
					</tr>
					<tr>
						<th>
							<label
								for="<?php echo esc_attr( MSWPSSO_Init::OPTION_TENANT ); ?>">Tenant</label>
						</th>
						<td>
							<input
								class="text"
								type="text"
								size="50"
								id="<?php echo esc_attr( MSWPSSO_Init::OPTION_TENANT ); ?>"
								name="<?php echo esc_attr( MSWPSSO_Init::OPTION_TENANT ); ?>"
								value="<?php echo esc_attr( get_option( MSWPSSO_Init::OPTION_TENANT ) ); ?>">
							<p class="description">The tenant selected in your MS Identity Platform App.</p>
						</td>
					</tr>
					<tr>
						<th>
							<label
								for="<?php echo esc_attr( MSWPSSO_Init::OPTION_CONTACT_EMAIL ); ?>">Email</label>
						</th>
						<td>
							<input
								class="email"
								type="email"
								size="50"
								id="<?php echo esc_attr( MSWPSSO_Init::OPTION_CONTACT_EMAIL ); ?>"
								name="<?php echo esc_attr( MSWPSSO_Init::OPTION_CONTACT_EMAIL ); ?>"
								value="<?php echo esc_attr( get_option( MSWPSSO_Init::OPTION_CONTACT_EMAIL ) ); ?>">
							<p class="description">The email for users to
								contact to request an account on this site.</p>
						</td>
					</tr>
					<tr>
						<th>
							<label
								for="<?php echo esc_attr( MSWPSSO_Init::OPTION_ERROR_PAGE_ID ); ?>">"No
								User Found" Page ID</label>
						</th>
						<td>
							<input
								class="text"
								type="text"
								size="10"
								id="<?php echo esc_attr( MSWPSSO_Init::OPTION_ERROR_PAGE_ID ); ?>"
								name="<?php echo esc_attr( MSWPSSO_Init::OPTION_ERROR_PAGE_ID ); ?>"
								value="<?php echo esc_attr( get_option( MSWPSSO_Init::OPTION_ERROR_PAGE_ID ) ); ?>">
							<p class="description">The ID for the page users
								will be
								redirected to when they do NOT have a matching
								account registered on the site.</p>
						</td>
					</tr>
					</tbody>
				</table>
				<?php
				submit_button();
				?>

			</form>

		</div>
		<?php
	}
}
