<?php
/**
 * Admin functionality class.
 *
 * @package EasySyntaxHighlighter
 */

namespace EasySyntaxHighlighter\Admin;

/**
 * Admin Class
 */
class Admin {

	/**
	 * Initialize the admin component.
	 */
	public function init() {
		add_filter( 'plugin_action_links_' . plugin_basename( ESH_PLUGIN_FILE ), array( $this, 'add_plugin_action_links' ) );
	}


	/**
	 * Add plugin action links.
	 *
	 * @param array $links Existing plugin action links.
	 * @return array Modified plugin action links.
	 */
	public function add_plugin_action_links( $links ) {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'options-general.php?page=easy-syntax-highlighter' ),
			__( 'Settings', 'easy-syntax-highlighter' )
		);

		array_unshift( $links, $settings_link );

		return $links;
	}
}

