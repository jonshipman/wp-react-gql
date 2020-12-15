<?php
/**
 * Admin settings
 *
 * @package WP React GQL
 * @since 1.0.0
 */

/**
 * Adds the page to the admin menu.
 *
 * @return void
 */
function wrg_admin_menu_recaptcha() {
	add_options_page(
		apply_filters( 'wrg_recaptcha_label', __( 'Recaptcha', 'wp-boilerplate-nodes' ) ),
		apply_filters( 'wrg_recaptcha_label', __( 'Recaptcha', 'wp-boilerplate-nodes' ) ),
		'manage_options',
		'wrg_recaptcha',
		'wrg_recaptcha_page',
	);
}

add_action( 'admin_menu', 'wrg_admin_menu_recaptcha' );

/**
 * Recaptcha settings output.
 *
 * @return void
 */
function wrg_recaptcha_page() {
	ob_start();
	settings_fields( 'wrg_recaptcha' );
	do_settings_sections( 'wrg_recaptcha' );
	submit_button();
	$settings = ob_get_clean();

	printf(
		'<div class="wrap"><h2>%s</h2><form action="options.php" method="post">%s</form></div>',
		esc_html( get_admin_page_title() ),
		$settings // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);
}

/**
 * Recaptcha settings fields.
 *
 * @return void
 */
function wrg_recaptcha_settings() {
	add_settings_section(
		'keys',
		__( 'Google Recaptcha', 'wp-react-gql' ),
		'__return_false',
		'wrg_recaptcha'
	);

	add_settings_field(
		'google_site_key',
		__( 'Site Key', 'wp-react-gql' ),
		function() {
			wrg_admin_settings_input( 'google_site_key' ); },
		'wrg_recaptcha',
		'keys',
		array( 'label_for' => 'google_site_key' )
	);

	add_settings_field(
		'google_secret_key',
		__( 'Secret Key', 'wp-react-gql' ),
		function() {
			wrg_admin_settings_input( 'google_secret_key' ); },
		'wrg_recaptcha',
		'keys',
		array( 'label_for' => 'google_secret_key' )
	);
}

add_action( 'admin_init', 'wrg_recaptcha_settings' );

/**
 * Settings form field output.
 *
 * @param string $field Field id being displayed.
 * @return void
 */
function wrg_admin_settings_input( $field ) {
	$value = get_option( $field );
	printf(
		'<input class="large-text" type="text" name="%s" id="%s" value="%s"> ',
		esc_attr( $field ),
		esc_attr( $field ),
		esc_attr( $value )
	);
}
