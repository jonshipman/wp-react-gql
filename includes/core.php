<?php
/**
 * Core functions
 *
 * @package WP React GQL
 * @since 1.0.0
 */

/**
 * WP-GraphQL Endpoint.
 *
 * @return string
 */
function wrg_get_gql_endpoint() {
	$filtered_endpoint = apply_filters( 'graphql_endpoint', null );
	$endpoint          = $filtered_endpoint ? $filtered_endpoint : get_graphql_setting( 'graphql_endpoint', 'graphql' );

	return home_url( $endpoint );
}

/**
 * Builds the __WP javascript object.
 *
 * @return void
 */
function wrg_wp_js() {
	$wordpress_window_object = wp_json_encode( apply_filters( 'wrg_wp_js_window', array() ) );

	printf( '<script type="text/javascript">window.__WP=%s;</script>', $wordpress_window_object ); // phpcs:ignore
}

add_action( 'wp_head', 'wrg_wp_js' );

/**
 * Filters the __WP window object.
 *
 * @param array $wp Associative array being filtered.
 * @return array
 */
function wrg_js_window_filter( $wp ) {
	$wp['GQLURL']    = wrg_get_gql_endpoint();
	$wp['THEME_URL'] = get_stylesheet_directory_uri();
	return $wp;
}

add_filter( 'wrg_wp_js_window', 'wrg_js_window_filter' );

/**
 * Dequeue block library as we're including it in the public folder for development.
 *
 * @return void
 */
function wrg_remove_block_library() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );

	if ( function_exists( 'is_woocommerce' ) ) {
		wp_dequeue_style( 'wc-block-style' );
		wp_dequeue_style( 'woocommerce-layout' );
		wp_dequeue_style( 'woocommerce-general' );
		wp_dequeue_style( 'woocommerce-smallscreen' );
		wp_dequeue_script( 'wc-cart-fragments' );
		wp_dequeue_script( 'woocommerce' );
		wp_dequeue_script( 'wc-add-to-cart' );

		wp_deregister_script( 'js-cookie' );
		wp_dequeue_script( 'js-cookie' );
	}
}

add_action( 'wp_enqueue_scripts', 'wrg_remove_block_library', PHP_INT_MAX );

/**
 * Removes the wp-embed script.
 *
 * @return void
 */
function wrg_deregister_scripts() {
	wp_dequeue_script( 'wp-embed' );
}

add_action( 'wp_footer', 'wrg_deregister_scripts' );

// Remove Emoji styles and scripts.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

// Prevents unwarranted redirects.
remove_action( 'template_redirect', 'redirect_canonical' );
remove_action( 'template_redirect', 'wp_redirect_admin_locations', 1000 );

// Prevent the adminbar from showing up.
add_filter( 'show_admin_bar', '__return_false', PHP_INT_MAX );

/**
 * Removes 404 for builtin pages.
 *
 * @return void
 */
function wrg_template_redirect() {
	global $wp_query;
	$current_uri    = trim( wp_parse_url( add_query_arg( array() ), PHP_URL_PATH ), '/' );
	$built_in_pages = apply_filters( 'wrg_built_in_pages', array( 'search', 'login', 'forgot-password', 'logout', 'register' ) );

	if ( ! empty( $current_uri ) && ( in_array( $current_uri, $built_in_pages, true ) || false !== strpos( $current_uri, 'rp/' ) ) ) {
		$wp_query->is_404 = false;
		status_header( 200 );
	}
}

add_action( 'template_redirect', 'wrg_template_redirect', PHP_INT_MAX );
