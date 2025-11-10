<?php
/**
 * Settings management class.
 *
 * @package EasySyntaxHighlighter
 */

namespace EasySyntaxHighlighter\Admin;

/**
 * SettingsPage Class
 */
class SettingsPage {

	/**
	 * Available highlight.js themes.
	 *
	 * @return array
	 */
	private function get_available_themes() {
		$themes = array(
			'a11y-light'               => 'A11y Light',
			'atom-one-light'           => 'Atom One Light',
			'arduino-light'            => 'Arduino Light',
			'ascetic'                  => 'Ascetic',
			'color-brewer'             => 'Color Brewer',
			'default'                  => 'Default',
			'devibeans'                => 'DeviBeans',
			'docco'                    => 'Docco',
			'far'                      => 'Far',
			'felipec'                  => 'Felipec',
			'foundation'               => 'Foundation',
			'github'                   => 'GitHub',
			'googlecode'               => 'GoogleCode',
			'gradient-light'           => 'Gradient Light',
			'grayscale'                => 'Grayscale',
			'idea'                     => 'Idea',
			'intellij-light'           => 'IntelliJ Light',
			'isbl-editor-light'        => 'ISBL Editor Light',
			'kimbie-light'             => 'Kimbie Light',
			'lightfair'                => 'Lightfair',
			'magula'                   => 'Magula',
			'mono-blue'                => 'Mono Blue',
			'nnfx-light'               => 'NNFX Light',
			'panda-syntax-light'       => 'Panda Syntax Light',
			'paraiso-light'            => 'Paraiso Light',
			'purebasic'                => 'PureBasic',
			'qtcreator-light'          => 'QtCreator Light',
			'rainbow'                  => 'Rainbow',
			'routeros'                 => 'RouterOS',
			'school-book'              => 'School Book',
			'stackoverflow-light'      => 'Stack Overflow Light',
			'srcery'                   => 'Srcery',
			'sunburst'                 => 'Sunburst',
			'vs'                       => 'Visual Studio',
			'xcode'                    => 'Xcode',
			'a11y-dark'                => 'A11y Dark',
			'agate'                    => 'Agate',
			'an-old-hope'              => 'An Old Hope',
			'androidstudio'            => 'Android Studio',
			'arta'                     => 'Arta',
			'atom-one-dark'            => 'Atom One Dark',
			'atom-one-dark-reasonable' => 'Atom One Dark Reasonable',
			'codepen-embed'            => 'CodePen Embed',
			'dark'                     => 'Dark',
			'github-dark'              => 'GitHub Dark',
			'github-dark-dimmed'       => 'GitHub Dark Dimmed',
			'gml'                      => 'GML',
			'gradient-dark'            => 'Gradient Dark',
			'hybrid'                   => 'Hybrid',
			'ir-black'                 => 'IR Black',
			'isbl-editor-dark'         => 'ISBL Editor Dark',
			'kimbie-dark'              => 'Kimbie Dark',
			'lioshi'                   => 'Lioshi',
			'monokai'                  => 'Monokai',
			'monokai-sublime'          => 'Monokai Sublime',
			'night-owl'                => 'Night Owl',
			'nnfx-dark'                => 'NNFX Dark',
			'nord'                     => 'Nord',
			'obsidian'                 => 'Obsidian',
			'panda-syntax-dark'        => 'Panda Syntax Dark',
			'paraiso-dark'             => 'Paraiso Dark',
			'qtcreator-dark'           => 'QtCreator Dark',
			'shades-of-purple'         => 'Shades of Purple',
			'stackoverflow-dark'       => 'Stack Overflow Dark',
			'tokyo-night-dark'         => 'Tokyo Night Dark',
			'tomorrow-night-blue'      => 'Tomorrow Night Blue',
			'tomorrow-night-bright'    => 'Tomorrow Night Bright',
			'vs2015'                   => 'Visual Studio 2015',
			'xt256'                    => 'XT256',
		);
		asort( $themes );
		return $themes;
	}

	/**
	 * Initialize the settings component.
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Add admin menu.
	 */
	public function add_admin_menu() {
		add_options_page(
			__( 'Easy Syntax Highlighter', 'easy-syntax-highlighter' ),
			__( 'Syntax Highlighter', 'easy-syntax-highlighter' ),
			'manage_options',
			'easy-syntax-highlighter',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register settings.
	 */
	public function register_settings() {
		register_setting(
			'esh_settings_group',
			'esh_settings',
			array( $this, 'sanitize_settings' )
		);
	}

	/**
	 * Sanitize settings.
	 *
	 * @param array $input Input settings.
	 * @return array Sanitized settings.
	 */
	public function sanitize_settings( $input ) {
		$sanitized = get_option( 'esh_settings', array() );
		$themes    = $this->get_available_themes();

		// Theme
		if ( isset( $input['theme'] ) && array_key_exists( $input['theme'], $themes ) ) {
			$sanitized['theme'] = sanitize_key( $input['theme'] );
		}

		return $sanitized;
	}

	/**
	 * Render the settings page.
	 */
	public function render_settings_page() {
		?>
		<div class="wrap esh-settings-wrap">
			<div class="esh-settings-header">
				<h1><?php echo esc_html__( 'Easy Syntax Highlighter', 'easy-syntax-highlighter' ); ?></h1>
				<div class="esh-settings-links">
					<a href="https://wordpress.org/plugins/easy-syntax-highlighter/" target="_blank" rel="noopener noreferrer" class="button button-secondary">
						<?php esc_html_e( 'WordPress.org Plugin Page', 'easy-syntax-highlighter' ); ?>
					</a>
					<a href="https://github.com/snowbedding/easy-syntax-highlighter" target="_blank" rel="noopener noreferrer" class="button button-secondary">
						<?php esc_html_e( 'GitHub Repository', 'easy-syntax-highlighter' ); ?>
					</a>
				</div>
			</div>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'esh_settings_group' );
				$this->render_theme_field();
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render the theme selector field.
	 */
	public function render_theme_field() {
		$options       = get_option( 'esh_settings' );
		$current_theme = $options['theme'] ?? 'github';
		$themes        = $this->get_available_themes();
		$preview_code  = <<<EOD
function helloWorld() {
  console.log("Hello, World!");
}
EOD;
		?>
		<input type="hidden" name="esh_settings[theme]" id="esh-selected-theme" value="<?php echo esc_attr( $current_theme ); ?>">
		<div class="esh-theme-selector">
			<div class="esh-theme-group">
				<h2><?php echo esc_html__( 'All Themes', 'easy-syntax-highlighter' ); ?></h2>
				<div class="esh-theme-grid">
					<?php foreach ( $themes as $key => $name ) : ?>
						<div class="esh-theme-preview <?php echo $current_theme === $key ? 'selected' : ''; ?>" data-theme="<?php echo esc_attr( $key ); ?>">
							<div class="esh-theme-header">
								<strong><?php echo esc_html( $name ); ?></strong>
							</div>
							<div class="esh-theme-preview-code">
								<?php
								$theme_url = ESH_PLUGIN_URL . 'assets/css/hljs-themes/' . esc_attr( $key ) . '.min.css';
								$iframe_srcdoc = sprintf(
									'<!DOCTYPE html><html><head><link rel="stylesheet" href="%s"><style>body{margin:0;padding:0;box-sizing:border-box;}pre{margin:0;padding:15px;font-size:12px;line-height:1.4;}code{font-family:Menlo,Monaco,Consolas,"Courier New",monospace;}</style></head><body><pre><code class="language-javascript">%s</code></pre></body></html>',
									$theme_url,
									esc_html( $preview_code )
								);
								?>
								<iframe
									class="esh-theme-preview-iframe"
									srcdoc="<?php echo esc_attr( $iframe_srcdoc ); ?>"
									style="width:100%; height:100px; border:none; display: block;"
									scrolling="no"
								></iframe>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
	}

}

