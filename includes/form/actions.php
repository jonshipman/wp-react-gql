<?php
/**
 * Form success filters.
 *
 * @package  wrg_
 */

/**
 * These should mirror the forms in $react/components/forms.
 * Add the nonce actions to this filter.
 *
 * @param array $forms Forms to filter.
 * @return array
 */
function wrg_nonce_default( $forms ) {
	$forms['default'] = 'default-contact-form';

	return $forms;
}

add_filter( 'wrg_nonce_actions', 'wrg_nonce_default' );

/**
 * ... and append to this array for the fields...
 *
 * @param array $fields Forms to filter.
 * @return array
 */
function wrg_fields_default( $fields ) {
	$fields['default'] = array(
		'yourName' => array(
			'type'        => 'String',
			'description' => __( 'Form submitter\'s name', 'wp-react-gql' ),
		),
		'email'    => array(
			'type'        => 'String',
			'description' => __( 'Form submitter\'s email', 'wp-react-gql' ),
		),
		'phone'    => array(
			'type'        => 'String',
			'description' => __( 'Form submitter\'s phone', 'wp-react-gql' ),
		),
		'message'  => array(
			'type'        => 'String',
			'description' => __( 'Form submitter\'s message', 'wp-react-gql' ),
		),
	);

	return $fields;
}

add_filter( 'wrg_fields', 'wrg_fields_default' );

/**
 * ...and another filter 'wrg_success_%FORMNAME%'
 * to handle your success. You can take this function and
 * reuse it.
 *
 * @param boolean $success Current action state.
 * @param array   $input Key => Value pairs for the submitted fields.
 * @return boolean
 */
function wrg_success_default( $success, $input ) {
	$message = wrg_input_to_text( $input );

	if ( $success ) {
		$success = wp_mail(
			apply_filters( 'wrg_default_to', get_option( 'admin_email' ) ),
			'Form Email',
			$message
		);
	}

	return $success;
}

add_filter( 'wrg_success_default', 'wrg_success_default', 10, 2 );

/**
 * Converts the key value input pairs to something that can be read in a mail message.
 *
 * @param array $input Fields submitted in the form.
 * @return string
 */
function wrg_input_to_text( $input ) {
	$walked = $input;
	array_walk(
		$walked,
		function( &$value, $key ) {
			$value = sprintf( "%s: %s\n", ucwords( $key ), $value );
		}
	);

	return implode( "\n", $walked );
}

/**
 * Add the form nonces to the window object.
 *
 * @param array $window_wp Form nonces.
 * @return array
 */
function wrg_add_nonces_to_window( $window_wp ) {
	$window_wp['form'] = array();

	foreach ( apply_filters( 'wrg_nonce_actions', array() ) as $form => $action ) {
		$window_wp['form'][ $form ] = wp_create_nonce( $action );
	}

	return $window_wp;
}

add_filter( 'wrg_wp_js_window', 'wrg_add_nonces_to_window' );

/**
 * Add recaptcha key to window.
 *
 * @param array $window_wp Form nonces.
 * @return array
 */
function wrg_add_recaptcha_to_window( $window_wp ) {
	$window_wp['recaptcha'] = get_option( 'google_site_key' ) ? get_option( 'google_site_key' ) : false;

	return $window_wp;
}

add_filter( 'wrg_wp_js_window', 'wrg_add_recaptcha_to_window' );
