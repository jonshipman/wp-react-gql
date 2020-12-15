<?php
/**
 * Plugin Name: WP React GQL Accessories
 * Description: Facilitates the nessesary WP hooks for https://github.com/jonshipman/react-wp-gql and react-wp-form
 * Version: 1.0.0
 * Author: Jon Shipman
 * Text Domain: wp-react-gql

 * ============================================================================================================
 * This software is provided "as is" and any express or implied warranties, including, but not limited to, the
 * implied warranties of merchantibility and fitness for a particular purpose are disclaimed. In no event shall
 * the copyright owner or contributors be liable for any direct, indirect, incidental, special, exemplary, or
 * consequential damages(including, but not limited to, procurement of substitute goods or services; loss of
 * use, data, or profits; or business interruption) however caused and on any theory of liability, whether in
 * contract, strict liability, or tort(including negligence or otherwise) arising in any way out of the use of
 * this software, even if advised of the possibility of such damage.

 * ============================================================================================================
 *
 * @package wp_boilerplate_nodes
 * @since 1.0.0
 */

define( 'WP_REACT_GQL', __FILE__ );

add_action(
	'plugins_loaded',
	function() {
		// Core functions required for react to work.
		require_once __DIR__ . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'core.php';

		// Authentication URLs and url changes in the email.
		require_once __DIR__ . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'index.php';

		// Form GQL and actions.
		require_once __DIR__ . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'form' . DIRECTORY_SEPARATOR . 'index.php';

		// Hooks for uploading from react-wp-form.
		require_once __DIR__ . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'upload.php';
	},
	11
);
