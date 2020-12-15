<?php
/**
 * Authentication functions
 *
 * @package WP React GQL
 * @since 1.0.0
 */

/**
 * Sets the login url.
 *
 * @return string
 */
function wrg_origin_login_url() {
	return home_url( 'login' );
}

add_filter( 'login_url', 'wrg_origin_login_url' );

/**
 * Sets the forgotpassword url.
 *
 * @return string
 */
function wrg_origin_forgot_password_url() {
	return home_url( 'forgot-password' );
}

add_filter( 'lostpassword_url', 'wrg_origin_forgot_password_url' );

/**
 * Sets the registration url.
 *
 * @return string
 */
function wrg_origin_register_url() {
	return home_url( 'register' );
}

add_filter( 'register_url', 'wrg_origin_register_url' );

/**
 * Filters the password reset email to change the retrieve password url.
 *
 * @param string $message Message to filter.
 * @param string $key Reset password key.
 * @param string $user_login Username for the user.
 * @return string
 */
function wrg_origin_rp_message( $message, $key, $user_login ) {
	$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

	$message = __( 'Someone has requested a password reset for the following account:' ) . "\r\n\r\n";
	/* translators: %s: Site name. */
	$message .= sprintf( __( 'Site Name: %s' ), $site_name ) . "\r\n\r\n";
	/* translators: %s: User login. */
	$message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
	$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "\r\n\r\n";
	$message .= __( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
	$message .= sprintf( '%s/rp/%s/%s', home_url(), $key, rawurlencode( $user_login ) ) . "\r\n";

	return $message;
}

add_filter( 'retrieve_password_message', 'wrg_origin_rp_message', 99, 3 );

/**
 * Filters the user registration email for the same.
 *
 * @param array   $wp_new_user_notification_email Associative array with the values used to create the new user email.
 * @param WP_User $user User object for the new user.
 * @return array
 */
function wrg_origin_new_user_message( $wp_new_user_notification_email, $user ) {
	$key = get_password_reset_key( $user );

	/* translators: %s: Username. */
	$message  = sprintf( __( 'Username: %s' ), $user->user_login ) . "\r\n\r\n";
	$message .= __( 'To set your password, visit the following address:' ) . "\r\n\r\n";
	$message .= sprintf( '%s/rp/%s/%s', home_url(), $key, rawurlencode( $user->user_login ) ) . "\r\n";

	$message .= wp_login_url() . "\r\n";

	$wp_new_user_notification_email['message'] = $message;

	return $wp_new_user_notification_email;
}

add_filter( 'wp_new_user_notification_email', 'wrg_origin_new_user_message', 99, 2 );
