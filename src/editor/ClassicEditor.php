<?php
/**
 * Classic Editor integration.
 *
 * @package EasySyntaxHighlighter
 */

namespace EasySyntaxHighlighter\Editor;

/**
 * ClassicEditor Class
 */
class ClassicEditor {

	/**
	 * Initialize the editor component.
	 */
	public function init() {
		// These hooks are for TinyMCE (Visual Editor)
		add_filter( 'mce_external_plugins', array( $this, 'add_tinymce_plugin' ) );
		add_filter( 'mce_buttons', array( $this, 'add_tinymce_button' ) );
	}

	/**
	 * Add TinyMCE plugin for the Visual view.
	 *
	 * @param array $plugins Existing plugins.
	 * @return array Modified plugins.
	 */
	public function add_tinymce_plugin( $plugins ) {
		$plugins['easy_syntax_highlighter'] = ESH_PLUGIN_URL . 'assets/js/tinymce-plugin.js';
		return $plugins;
	}

	/**
	 * Add TinyMCE button to the toolbar.
	 *
	 * @param array $buttons Existing buttons.
	 * @return array Modified buttons.
	 */
	public function add_tinymce_button( $buttons ) {
		array_push( $buttons, 'esh_code_button' );
		return $buttons;
	}
}
