/**
 * Easy Syntax Highlighter Admin JavaScript
 */
( function( $ ) {
	'use strict';

	$( document ).ready(
		function() {
			const themeSelector = $( '.esh-theme-selector' );
			const selectedThemeInput = $( '#esh-selected-theme' );

			// Force refresh all theme preview iframes to load CSS resources
			function forceRefreshThemePreviews() {
				const timestamp = Date.now();
				$('.esh-theme-preview-iframe').each(function() {
					const iframe = $(this);
					const themePreview = iframe.closest('.esh-theme-preview');
					const themeName = themePreview.data('theme');
					const themeUrl = eshAdminVars.pluginUrl + 'assets/css/hljs-themes/' + themeName + '.min.css?t=' + timestamp;

					// Regenerate srcdoc with timestamp to force CSS reload
					const previewCode = 'function helloWorld() {\n  console.log("Hello, World!");\n}';
					const iframeSrcdoc = '<!DOCTYPE html><html><head><link rel="stylesheet" href="' + themeUrl + '"><style>body{margin:0;padding:0;box-sizing:border-box;}pre{margin:0;padding:15px;font-size:12px;line-height:1.4;}code{font-family:Menlo,Monaco,Consolas,"Courier New",monospace;}</style></head><body><pre><code class="language-javascript">' + previewCode + '</code></pre></body></html>';

					iframe.attr('srcdoc', iframeSrcdoc);
				});
			}

			// Force refresh on page load to ensure all theme CSS is loaded fresh
			forceRefreshThemePreviews();

			// Handle theme selection via click.
			themeSelector.on( 'click', '.esh-theme-preview', function() {
				const clickedTheme = $( this );
				const themeName = clickedTheme.data( 'theme' );

				// Update UI.
				themeSelector.find( '.esh-theme-preview' ).removeClass( 'selected' );
				clickedTheme.addClass( 'selected' );

				// Update hidden input.
				selectedThemeInput.val( themeName ).trigger( 'change' );
			} );

			// Highlight all preview snippets on page load.
			if (typeof hljs !== 'undefined') {
				$('.esh-theme-preview-iframe').on('load', function() {
					try {
						const doc = this.contentWindow.document;
						const codeBlock = doc.querySelector('code');
						if (codeBlock) {
							hljs.highlightElement(codeBlock);
						}
					} catch (e) {
						// eslint-disable-next-line no-console
						console.error('Could not highlight code in iframe.', e);
					}
				});
			}
		}
	);

}( jQuery ) );

