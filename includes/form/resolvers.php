<?php
/**
 * Form GraphQL resolver.
 *
 * @package WP React GQL
 */

/**
 * Registers the formData field to get the site key and nonces.
 *
 * @param \WPGraphQL\Registry\TypeRegistry $type_registry WPGraphQL Type Registry.
 * @return void
 */
function wrg_register_fields( \WPGraphQL\Registry\TypeRegistry $type_registry ) {
	register_graphql_field(
		'RootQuery',
		'formData',
		array(
			'type'        => 'FormType',
			'description' => __( 'Handles form pre-population data', 'wp-react-gql' ),
			'resolve'     => function ( $source ) {
				$res = array(
					'id'              => \GraphQLRelay\Relay::toGlobalId( 'formdata', 1 ),
					'wpNonce'         => array(),
					'recatchaSiteKey' => get_option( 'google_site_key' ) ?: '',
				);

				foreach ( apply_filters( 'wrg_nonce_actions', array() ) as $form => $action ) {
					$res['wpNonce'][] = array(
						'id'      => \GraphQLRelay\Relay::toGlobalId( 'nonce', $action ),
						'form'    => $form,
						'wpNonce' => wp_create_nonce( $action ),
					);
				}

				return $res;
			},
		)
	);
}

add_action( 'graphql_register_types', 'wrg_register_fields' );
