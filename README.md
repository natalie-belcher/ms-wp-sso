# MS Single Sign-on for WP

Created by Natalie Belcher, 2023

A WordPress Plugin for Single Sign On (SSO) using Microsoft Active Directory accounts.

---

## Setup

In order to set up this plugin, you will first need to register your App with the Microsoft Identity Platform: https://learn.microsoft.com/en-us/entra/identity-platform/quickstart-register-app

You will need to add the redirect URI found in the plugin settings page to your app registered on Microsoft Identity Platform.

Once you have completed the app registration you will need the Client ID from it to add on the plugin settings page.

You will also need to set the tenant selected in your app on the plugin settings page.

Finally, you will need to create an error page where users will be redirected should no matching user account be found in the WordPress database during login with Microsoft. This page should be set to noindex and you will need the page ID to add to the plugin settings page.

