/**
 * Created by ADMIN on 12/15/2016.
 */
jQuery(document).ready(function() {
    if (jQuery('.set_custom_images').length > 0) {
        if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
            jQuery(document).on('click', '.set_custom_images', function(e) {
                e.preventDefault();
                var button = jQuery(this);
                var id = button.prev();
                wp.media.editor.send.attachment = function(props, attachment) {
                    id.val(attachment.id);
                };
                wp.media.editor.open(button);
                return false;
            });
        }
    }
});