/**
 * TinyMCE Plugin for Easy Syntax Highlighter
 */

(function() {
    tinymce.PluginManager.add('easy_syntax_highlighter', function(editor, url) {
        editor.addButton('esh_code_button', {
            title: 'Insert Code Block',
            icon: 'code',
            onclick: function() {
                if (typeof window.eshOpenModal !== 'undefined') {
                    window.eshOpenModal(editor);
                } else {
                    // Fallback for safety
                    var selectedText = editor.selection.getContent({
                        'format': 'text'
                    });
                    var codeBlock = '<pre><code>' + selectedText + '</code></pre>';
                    editor.execCommand('mceInsertContent', false, codeBlock);
                }
            }
        });
    });
})();
