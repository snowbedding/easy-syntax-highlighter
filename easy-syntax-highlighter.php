<?php
/**
 * Plugin Name: Easy Syntax Highlighter
 * Plugin URI:  https://github.com/snowbedding/easy-syntax-highlighter
 * Description: A modern, lightweight, and powerful syntax highlighter for WordPress using Highlight.js. Completely rewritten for performance and extensibility.
 * Version:     2.0.0
 * Author:      snowbedding
 * Author URI:  https://github.com/snowbedding
 * License:     GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: easy-syntax-highlighter
 * Requires at least: 5.0
 * Tested up to: 6.9
 * Requires PHP: 7.0
 * Domain Path: /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'ESH_VERSION', '2.0.0' );
define( 'ESH_PLUGIN_FILE', __FILE__ );
define( 'ESH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ESH_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ESH_TEXT_DOMAIN', 'easy-syntax-highlighter' );

// PSR-4 Autoloader.
spl_autoload_register(
	function ( $class ) {
		$prefix   = 'EasySyntaxHighlighter\\';
		$base_dir = __DIR__ . '/src/';

		$len = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}

		$relative_class = substr( $class, $len );
		$file           = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function esh_run_plugin() {
	$plugin = new EasySyntaxHighlighter\Core\Plugin();
	$plugin->run();
}

esh_run_plugin();

/**
 * Plugin uninstall cleanup.
 *
 * This code runs when the plugin is uninstalled via the WordPress admin.
 * It cleans up all plugin-related data from the database.
 *
 * @since 2.0.0
 */
if (defined('WP_UNINSTALL_PLUGIN')) {
    // Exit if not called for this plugin.
    if (!isset($_REQUEST['plugin']) || $_REQUEST['plugin'] !== 'easy-syntax-highlighter/easy-syntax-highlighter.php') {
        exit;
    }

    // Only proceed if user has the capability to delete plugins.
    if (!current_user_can('delete_plugins')) {
        exit;
    }

    /**
     * Clean up plugin data.
     */
    function easy_syntax_highlighter_uninstall() {
        // Delete plugin settings
        delete_option('esh_settings');

        // Clean up any cached data
        wp_cache_flush();

        // Note: We don't delete user content (posts with shortcodes or blocks)
        // as this could be destructive. Users should manually clean up content
        // if they want to remove all traces of the plugin.
    }

    easy_syntax_highlighter_uninstall();
}