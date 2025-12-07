<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @package    EasySyntaxHighlighter
 * @subpackage EasySyntaxHighlighter/Core
 * @author     snowbedding & Gemini
 */

namespace EasySyntaxHighlighter\Core;

/**
 * The core plugin class.
 */
class Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * Get the plugin name.
	 *
	 * @return string The plugin name.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The current version of the plugin.
	 *
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The asset manager for handling CSS/JS assets.
	 *
	 * @var      AssetManager    $asset_manager    Handles plugin assets.
	 */
	protected $asset_manager;

	/**
	 * The admin functionality handler.
	 *
	 * @var      \EasySyntaxHighlighter\Admin\Admin    $admin    Handles admin functionality.
	 */
	protected $admin;

	/**
	 * The settings page handler.
	 *
	 * @var      \EasySyntaxHighlighter\Admin\SettingsPage    $settings_page    Handles settings page.
	 */
	protected $settings_page;

	/**
	 * The classic editor integration handler.
	 *
	 * @var      \EasySyntaxHighlighter\Editor\ClassicEditor    $classic_editor    Handles classic editor integration.
	 */
	protected $classic_editor;

	/**
	 * Define the core functionality of the plugin.
	 */
	public function __construct() {
		$this->version     = ESH_VERSION;
		$this->plugin_name = 'easy-syntax-highlighter';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_editor_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 */
	private function load_dependencies() {
		$this->loader         = new \EasySyntaxHighlighter\Core\Loader();
		$this->asset_manager  = new \EasySyntaxHighlighter\Core\AssetManager();
		$this->admin          = new \EasySyntaxHighlighter\Admin\Admin();
		$this->settings_page  = new \EasySyntaxHighlighter\Admin\SettingsPage();
		$this->classic_editor = new \EasySyntaxHighlighter\Editor\ClassicEditor();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 */
	private function set_locale() {
		$this->loader->add_action( 'plugins_loaded', $this, 'load_textdomain' );
		$this->loader->add_filter( 'all_plugins', $this, 'translate_plugin_description' );
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			ESH_TEXT_DOMAIN,
			false,
			dirname( plugin_basename( ESH_PLUGIN_FILE ) ) . '/languages/'
		);
	}

	/**
	 * Translate plugin description in the plugins list page.
	 *
	 * @param array $plugins All plugins data.
	 * @return array Modified plugins data with translated description.
	 */
	public function translate_plugin_description( $plugins ) {
		$plugin_file = plugin_basename( ESH_PLUGIN_FILE );

		if ( isset( $plugins[ $plugin_file ] ) ) {
			$plugins[ $plugin_file ]['Description'] = __( 'A modern, lightweight, and powerful syntax highlighter for WordPress using Highlight.js.', ESH_TEXT_DOMAIN );
		}

		return $plugins;
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 */
	private function define_admin_hooks() {
		$this->loader->add_action( 'plugins_loaded', $this->asset_manager, 'init' );
		$this->loader->add_action( 'plugins_loaded', $this->admin, 'init' );
		$this->loader->add_action( 'plugins_loaded', $this->settings_page, 'init' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 */
	private function define_public_hooks() {
		// No public hooks needed - functionality provided through blocks/Gutenberg
	}

	/**
	 * Register all of the hooks related to the editor functionality.
	 */
	private function define_editor_hooks() {
		$this->loader->add_action( 'plugins_loaded', $this->classic_editor, 'init' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		register_activation_hook( ESH_PLUGIN_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( ESH_PLUGIN_FILE, array( $this, 'deactivate' ) );

		$this->loader->run();
	}

	/**
	 * The code that runs during plugin activation.
	 */
	public function activate() {
		$default_settings = array(
			'theme' => 'github',
		);
		add_option( 'esh_settings', $default_settings );
	}

	/**
	 * The code that runs during plugin deactivation.
	 */
	public function deactivate() {
		// Nothing to do here for now.
	}
}
