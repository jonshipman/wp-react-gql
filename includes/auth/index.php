<?php
/**
 * Authentication
 *
 * @package WP React GQL
 * @since 1.0.0
 */

// Authentication URLs and url changes in the email.
require_once __DIR__ . DIRECTORY_SEPARATOR . 'routing.php';

// GraphQL Mutation to login.
require_once __DIR__ . DIRECTORY_SEPARATOR . 'login.php';

// GraphQL Mutation to logout.
require_once __DIR__ . DIRECTORY_SEPARATOR . 'logout.php';
