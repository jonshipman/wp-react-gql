<?php
/**
 * Form GraphQL type.
 *
 * @package WP React GQL
 */

/**
 * Register the type associations.
 *
 * @return void
 */
function wrg_register_types() {
	register_graphql_object_type(
		'WpNonce',
		array(
			'description' => __( 'Nonces for the forms', 'wp-react-gql' ),
			'fields'      => array(
				'id'      => array(
					'type'        => 'ID',
					'description' => __( 'Unique id useful for cache merging', 'wp-react-gql' ),
				),
				'form'    => array(
					'type'        => 'String',
					'description' => __( 'Form attached to nonce', 'wp-react-gql' ),
				),
				'wpNonce' => array(
					'type'        => 'String',
					'description' => __( 'Nonce value', 'wp-react-gql' ),
				),
			),
		)
	);

	register_graphql_object_type(
		'FormType',
		array(
			'description' => __( 'Support for the form actions over GraphQL', 'wp-react-gql' ),
			'fields'      => array(
				'id'              => array(
					'type'        => 'ID',
					'description' => __( 'Unique id useful for cache merging', 'wp-react-gql' ),
				),
				'wpNonce'         => array(
					'type'        => array( 'list_of' => 'WpNonce' ),
					'description' => __( 'Current nonce for session', 'wp-react-gql' ),
				),
				'recatchaSiteKey' => array(
					'type'        => 'String',
					'description' => __( 'Recaptcha Site Key', 'wp-react-gql' ),
				),
			),
		)
	);
}

add_action( 'graphql_register_types', 'wrg_register_types' );
