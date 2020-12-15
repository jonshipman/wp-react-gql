<?php
/**
 * Login with Cookies
 *
 * GraphQL resolve to login with cookies.
 *
 * @link https://github.com/funkhaus/wp-graphql-cors
 *
 * @package WP React GQL
 * @since 1.0.0
 */

/**
 * GraphQL resolve to login with cookies.
 *
 * @return void
 */
function wrg_login_with_cookies() {
	register_graphql_mutation(
		'login',
		array(
			'inputFields'         => array(
				'login'      => array(
					'type'        => array( 'non_null' => 'String' ),
					'description' => __( 'Input your user/e-mail.', 'react-build' ),
				),
				'password'   => array(
					'type'        => array( 'non_null' => 'String' ),
					'description' => __( 'Input your password.', 'react-build' ),
				),
				'rememberMe' => array(
					'type'        => 'Boolean',
					'description' => __(
						'Whether to "remember" the user. Increases the time that the cookie will be kept. Default false.',
						'react-build'
					),
				),
			),
			'outputFields'        => array(
				'status' => array(
					'type'        => 'String',
					'description' => 'Login operation status',
					'resolve'     => function( $payload ) {
						return $payload['status'];
					},
				),
				'viewer' => array(
					'type'        => 'User',
					'description' => __( 'Returns the current user', 'react-build' ),
					'resolve'     => function( $source, array $args, $context, $info ) {
						$id = $source['user_id'];
						return \WPGraphQL\Data\DataSource::resolve_user( $id, $context );
					},
				),
			),
			'mutateAndGetPayload' => function( $input ) {
				// Prepare credentials.
				$credential_keys = array(
					'login'      => 'user_login',
					'password'   => 'user_password',
					'rememberMe' => 'remember',
				);

				$credentials     = array();
				foreach ( $input as $key => $value ) {
					if ( in_array( $key, array_keys( $credential_keys ), true ) ) {
						$credentials[ $credential_keys[ $key ] ] = $value;
					}
				}

				// Authenticate User.
				$user = wp_signon( $credentials, is_ssl() );

				if ( is_wp_error( $user ) ) {
					throw new \GraphQL\Error\UserError( ! empty( $user->get_error_code() ) ? $user->get_error_code() : 'invalid login' );
				}

				$user_login = $credentials['user_login'];
				wp_set_current_user( $user->ID, $user_login );
				wp_set_auth_cookie( $user->ID, true );
				do_action( 'wp_login', $user_login, $user );

				return array(
					'status'  => 'SUCCESS',
					'user_id' => $user->ID,
				);
			},
		)
	);
}

add_action( 'graphql_register_types', 'wrg_login_with_cookies' );

/**
 * Query for a boolean is logged in return.
 *
 * @return void
 */
function wrg_is_logged_in() {
	register_graphql_field(
		'RootQuery',
		'IsLoggedIn',
		array(
			'type'        => array( 'non_null' => 'Boolean' ),
			'description' => __( 'Simple resolve that returns is_user_logged_in', 'react-build' ),
			'resolve'     => function() {
				return is_user_logged_in();
			},
		)
	);
}

add_action( 'graphql_register_types', 'wrg_is_logged_in' );
