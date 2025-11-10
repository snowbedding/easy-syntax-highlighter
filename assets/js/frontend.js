/**
 * Easy Syntax Highlighter Frontend JavaScript
 */

/**
 * Double-click to copy code functionality
 */
(function($) {
    'use strict';

    // Double-click to copy code
    $(document).on('dblclick', 'pre code', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $code = $(this);
        const $pre = $code.closest('pre');
        const $wrapper = $pre.closest('.esh-code-wrapper');

        // Get the text content (removing any HTML entities that might be present)
        const codeText = $code.text();

        // Copy to clipboard
        copyToClipboard(codeText).then(function() {
            // Show success feedback
            showCopyFeedback($pre);
        }).catch(function(err) {
            console.error('Failed to copy code: ', err);
            // Fallback: show error feedback
            showCopyFeedback($pre, false);
        });
    });

    /**
     * Copy text to clipboard using modern API with fallback
     */
    function copyToClipboard(text) {
        // Modern Clipboard API
        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(text);
        }

        // Fallback for older browsers or non-secure contexts
        return new Promise(function(resolve, reject) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                document.body.removeChild(textArea);
                if (successful) {
                    resolve();
                } else {
                    reject(new Error('Fallback copy failed'));
                }
            } catch (err) {
                document.body.removeChild(textArea);
                reject(err);
            }
        });
    }

    /**
     * Show copy feedback to user
     */
    function showCopyFeedback($element, success = true) {
        const feedbackText = success ? esh_i18n.copied : esh_i18n.copy_failed;
        const feedbackClass = success ? 'esh-copy-success' : 'esh-copy-error';

        // Remove existing feedback
        $element.find('.esh-copy-feedback').remove();

        // Create feedback element
        const $feedback = $('<div class="esh-copy-feedback ' + feedbackClass + '">' + feedbackText + '</div>');

        // Position and style
        $feedback.css({
            position: 'absolute',
            top: '50%',
            left: '50%',
            transform: 'translate(-50%, -50%)',
            background: success ? 'rgba(40, 167, 69, 0.9)' : 'rgba(220, 53, 69, 0.9)',
            color: 'white',
            padding: '8px 16px',
            borderRadius: '4px',
            fontSize: '14px',
            fontWeight: 'bold',
            pointerEvents: 'none',
            zIndex: 1000,
            opacity: 0,
            transition: 'opacity 0.3s ease'
        });

        // Make element relatively positioned if not already
        if ($element.css('position') === 'static') {
            $element.css('position', 'relative');
        }

        // Add to DOM
        $element.append($feedback);

        // Animate in
        setTimeout(function() {
            $feedback.css('opacity', '1');
        }, 10);

        // Remove after delay
        setTimeout(function() {
            $feedback.css('opacity', '0');
            setTimeout(function() {
                $feedback.remove();
            }, 300);
        }, 2000);
    }

})(jQuery);

