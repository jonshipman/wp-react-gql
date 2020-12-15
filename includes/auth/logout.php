<?php
/**
 * Logout
 *
 * GraphQL resolve to logout.
 *
 * @link https://github.com/funkhaus/wp-graphql-cors
 *
 * @package React Build
 * @since 1.0.0
 */

/**
 * Used to logout.
 *
 * @return void
 */
function wrg_logout_resolve() {
	register_graphql_mutation(
		'logout',
		array(
			'inputFields'         => array(),
			'outputFields'        => array(
				'status' => array(
					'type'        => 'String',
					'description' => 'Logout operation status',
					'resolve'     => function( $payload ) {
						return $payload['status'];
					},
				),
			),
			'mutateAndGetPayload' => function() {
				// Logout and destroy session.
				wp_logout();

				return array( 'status' => 'SUCCESS' );
			},
		)
	);
}

add_action( 'graphql_register_types', 'wrg_logout_resolve' );
