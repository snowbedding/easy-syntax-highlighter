/**
 * Easy Syntax Highlighter Modal JavaScript
 */
(function ($) {
    'use strict';

    // To be populated from PHP
    var eshModalData = window.eshModalData || {
        languages: {
            '': 'Auto-detect',
            'apache': 'Apache',
            'bash': 'Bash',
            'c': 'C',
            'cpp': 'C++',
            'csharp': 'C#',
            'css': 'CSS',
            'diff': 'Diff',
            'go': 'Go',
            'html': 'HTML',
            'java': 'Java',
            'javascript': 'JavaScript',
            'json': 'JSON',
            'kotlin': 'Kotlin',
            'less': 'Less',
            'lua': 'Lua',
            'makefile': 'Makefile',
            'markdown': 'Markdown',
            'nginx': 'Nginx',
            'objectivec': 'Objective-C',
            'perl': 'Perl',
            'php': 'PHP',
            'powershell': 'PowerShell',
            'python': 'Python',
            'ruby': 'Ruby',
            'rust': 'Rust',
            'scss': 'SCSS',
            'shell': 'Shell',
            'sql': 'SQL',
            'swift': 'Swift',
            'typescript': 'TypeScript',
            'vbnet': 'VB.NET',
            'xml': 'XML',
            'yaml': 'YAML'
        }
    };

    var editorInstance; // To hold the TinyMCE editor instance

    // Main modal object
    var ESH_Modal = {
        init: function () {
            // Create the modal HTML and append it to the body
            this.buildModal();

            // Bind events
            $('#esh-modal-close, #esh-modal-backdrop').on('click', this.close);
            $('#esh-modal-insert').on('click', this.insert);
            $(document).on('keydown', function (e) {
                if (e.key === "Escape") {
                    ESH_Modal.close();
                }
            });
        },

        buildModal: function () {
            var languageOptions = '';
            for (var lang in eshModalData.languages) {
                languageOptions += '<option value="' + lang + '">' + eshModalData.languages[lang] + '</option>';
            }

            var modalHtml =
                '<div id="esh-modal-backdrop" class="esh-modal-backdrop" style="display: none;"></div>' +
                '<div id="esh-modal-wrap" class="esh-modal-wrap" style="display: none;">' +
                '    <div class="esh-modal-header">' +
                '        <h2>Insert Code</h2>' +
                '        <button id="esh-modal-close" type="button" class="esh-modal-close">&times;</button>' +
                '    </div>' +
                '    <div class="esh-modal-body">' +
                '        <div class="esh-form-group">' +
                '            <label for="esh-modal-language">Language</label>' +
                '            <select id="esh-modal-language">' + languageOptions + '</select>' +
                '        </div>' +
                '        <div class="esh-form-group">' +
                '            <label for="esh-modal-code">Code</label>' +
                '            <textarea id="esh-modal-code" placeholder="Paste your code here..."></textarea>' +
                '        </div>' +
                '    </div>' +
                '    <div class="esh-modal-footer">' +
                '        <button id="esh-modal-cancel" type="button" class="button">Cancel</button>' +
                '        <button id="esh-modal-insert" type="button" class="button button-primary">Insert</button>' +
                '    </div>' +
                '</div>';

            $('body').append(modalHtml);
            $('#esh-modal-cancel').on('click', this.close);

        },

        open: function (editor) {
            editorInstance = editor;
            $('#esh-modal-backdrop, #esh-modal-wrap').fadeIn(200);
            $('#esh-modal-code').val('').focus();
        },

        close: function () {
            $('#esh-modal-backdrop, #esh-modal-wrap').fadeOut(200);
            editorInstance = null;
        },

        insert: function () {
            var lang = $('#esh-modal-language').val();
            var code = $('#esh-modal-code').val();

            if (code.trim() === '') {
                ESH_Modal.close();
                return;
            }

            // The 'language-xxx' class is what highlight.js uses.
            // If no language is selected, we omit the class for auto-detection.
            var langClass = lang ? 'language-' + lang : '';
            
            // We need to escape the HTML entities for the code block.
            var escapedCode = $('<div/>').text(code).html();

            var content = '<pre><code class="' + langClass + '">' + escapedCode + '</code></pre>\n';

            if (editorInstance) {
                editorInstance.execCommand('mceInsertContent', false, content);
            }

            ESH_Modal.close();
        }
    };

    // Initialize the modal logic
    $(document).ready(function () {
        ESH_Modal.init();
    });

    // Make the open function globally accessible for the TinyMCE plugin
    window.eshOpenModal = ESH_Modal.open;

})(jQuery);
