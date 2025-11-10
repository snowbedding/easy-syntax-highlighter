<?php
/**
 * AssetManager management class.
 *
 * @package EasySyntaxHighlighter
 */

namespace EasySyntaxHighlighter\Core;

/**
 * AssetManager Class
 */
class AssetManager {

	/**
	 * Initialize the assets component.
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	/**
	 * Enqueue frontend assets.
	 */
	public function enqueue_frontend_assets() {
		// Only enqueue on singular pages for now to avoid loading on archives, etc.
		if ( ! is_singular() ) {
			return;
		}

		// Ensure text domain is loaded
		if ( ! is_textdomain_loaded( 'easy-syntax-highlighter' ) ) {
			load_plugin_textdomain( 'easy-syntax-highlighter', false, dirname( plugin_basename( __FILE__ ) ) . '/../languages/' );
		}

		$options = get_option( 'esh_settings', array() );
		$theme   = $options['theme'] ?? 'github'; // Default to github theme

		// Enqueue highlight.js core script
		wp_enqueue_script(
			'esh-highlight-js',
			ESH_PLUGIN_URL . 'assets/js/highlight.min.js',
			array(),
			'11.9.0',
			true
		);

		// Enqueue highlight.js theme from local files
		wp_enqueue_style(
			'esh-highlight-js-theme',
			ESH_PLUGIN_URL . 'assets/css/hljs-themes/' . $theme . '.min.css',
			array(),
			ESH_VERSION
		);

		// Enqueue custom frontend styles for wrapper/title
		wp_enqueue_style(
			'esh-frontend',
			ESH_PLUGIN_URL . 'assets/css/frontend.css',
			array(),
			ESH_VERSION
		);

		// Enqueue frontend JavaScript for double-click copy functionality
		wp_enqueue_script(
			'esh-frontend',
			ESH_PLUGIN_URL . 'assets/js/frontend.js',
			array( 'jquery' ),
			ESH_VERSION,
			true
		);

		// Localize script with translation strings
		$locale = get_locale();

		// For English locales, force English translations
		if (strpos($locale, 'en') === 0) {
			$copied_text = 'Copied!';
			$copy_failed_text = 'Copy failed';
		} else {
			$copied_text = __( '已复制！', 'easy-syntax-highlighter' );
			$copy_failed_text = __( '复制失败', 'easy-syntax-highlighter' );
		}

		wp_localize_script(
			'esh-frontend',
			'esh_i18n',
			array(
				'copied'      => $copied_text,
				'copy_failed' => $copy_failed_text,
			)
		);

		// Initialize highlight.js
		wp_add_inline_script( 'esh-highlight-js', 'document.addEventListener("DOMContentLoaded", () => { hljs.highlightAll(); });' );
	}

	/**
	 * Enqueue admin assets.
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( 'settings_page_easy-syntax-highlighter' === $hook ) {
			// For live theme preview
			wp_enqueue_script(
				'esh-highlight-js',
				ESH_PLUGIN_URL . 'assets/js/highlight.min.js',
				array(),
				'11.9.0',
				true
			);
		}

		// Only enqueue on plugin settings page and post editing pages
		if ( 'settings_page_easy-syntax-highlighter' === $hook ||
			'post.php' === $hook ||
			'post-new.php' === $hook ) {

			wp_enqueue_style(
				'esh-admin',
				ESH_PLUGIN_URL . 'assets/css/admin.css',
				array(),
				ESH_VERSION
			);

			if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
				wp_enqueue_style(
					'esh-modal',
					ESH_PLUGIN_URL . 'assets/css/modal.css',
					array(),
					ESH_VERSION
				);
				wp_enqueue_script(
					'esh-modal',
					ESH_PLUGIN_URL . 'assets/js/modal.js',
					array( 'jquery' ),
					ESH_VERSION,
					true
				);
			}

			// Admin JS will be needed for the new modal
			wp_enqueue_script(
				'esh-admin',
				ESH_PLUGIN_URL . 'assets/js/admin.js',
				array( 'jquery' ),
				ESH_VERSION,
				true
			);

			// Localize script with plugin URL for theme CSS loading
			wp_localize_script(
				'esh-admin',
				'eshAdminVars',
				array(
					'pluginUrl' => ESH_PLUGIN_URL,
				)
			);
		}
	}
}
