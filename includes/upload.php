<?php
/**
 * Uploads
 *
 * @package WP React GQL
 * @since 1.0.0
 */

/**
 * Creates an action for uploads.
 *
 * @return void
 */
function wrg_media_upload_action() {
	do_action( 'wrg_preflight' );

	if ( current_user_can( 'upload_files' ) ) {
		if ( isset( $_FILES['file'] ) ) {
			$file = wp_unslash( $_FILES['file'] );
			$mime = mime_content_type( $file['tmp_name'] );

			do_action( 'wrg_media_upload', $file, $mime );
			die;
		}
	} else {
		header( 'HTTP/1.1 403 FORBIDDEN' );
		echo wp_json_encode( array( 'message' => '403 Forbidden' ) );
	}

	die;
}

/**
 * Allows us to preflight from the frontend with cors.
 *
 * @return void
 */
function wrg_preflight() {
	$request_method = isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : 'GET';

	$origin = get_http_origin();

	if ( is_allowed_http_origin( $origin ) ) {
		header( 'Access-Control-Allow-Origin: ' . $origin );
		header( 'Access-Control-Allow-Credentials: true' );
		header( 'Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE' );
		header( 'Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Credentials, Origin, Authorization, Credentials, Content-Type' );
		http_response_code( 200 );

		if ( 'OPTIONS' === $request_method ) {
			exit;
		}
	}
}

add_action( 'wrg_preflight', 'wrg_preflight' );

/**
 * By wrapping the template redirect in this action, we can override in theme without url sniffing.
 *
 * @return void
 */
function wrg_media_upload_template_redirect() {
	add_action( 'template_redirect', 'wrg_media_upload_action' );
}

add_action( 'wrg_media_upload_template_redirect', 'wrg_media_upload_template_redirect' );

/**
 * Helper that will load the template redirect in case of another hook conflict.
 *
 * @return void
 */
function wrg_action_redirect_helper() {
	$url_path = trim( wp_parse_url( add_query_arg( array() ), PHP_URL_PATH ), '/' );

	if ( 0 === stripos( $url_path, 'wrg_ajax' ) ) {
		remove_all_actions( 'template_redirect' );
		do_action( 'wrg_media_upload_template_redirect' );
	}
}

add_action( 'init', 'wrg_action_redirect_helper' );

/**
 * Used in ajax. Takes a file and puts it in the media gallery.
 *
 * @param string $file Contents of $_FILE.
 * @param string $mime Mime of uploaded file.
 * @return void
 */
function wrg_media_library_upload( $file, $mime ) {
	if ( in_array( $mime, get_allowed_mime_types(), true ) ) {
		$upload   = wp_upload_dir();
		$new_file = sprintf( '%s/%s', $upload['path'], $file['name'] );

		if ( move_uploaded_file( $file['tmp_name'], $new_file ) ) {
			$id = wp_insert_attachment(
				array(
					'guid'           => $new_file,
					'post_mime_type' => $mime,
					'post_title'     => preg_replace( '/\.[^.]+$/', '', $file['name'] ),
					'post_content'   => '',
					'post_status'    => 'inherit',
				),
				$new_file
			);

			if ( $id && ! is_wp_error( $id ) ) {
				include ABSPATH . 'wp-admin/includes/image.php';
			}

			wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $new_file ) );

			header( 'HTTP/1.1 200 OK' );
			echo wp_json_encode( $id );
			return;
		}

		header( 'HTTP/1.1 500 UPLOAD NOT MOVED' );
		echo 0;
		return;
	}

	header( 'HTTP/1.1 403 FORBIDDEN' );
	echo 0;
}


add_action( 'wrg_media_upload', 'wrg_media_library_upload', 10, 2 );
