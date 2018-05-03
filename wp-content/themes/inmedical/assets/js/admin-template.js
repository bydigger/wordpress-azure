(function ($) {
	"use strict";

    $(document).ready(function () {

        function media_upload(button_class) {

            if(!wp.media){
                return;
            }
            if(!wp.media.editor){
                return;
            }

            var _custom_media = true,
                _orig_send_attachment = wp.media.editor.send.attachment;

            $('body').on('click', button_class, function(e) {
                var button_id ='#'+$(this).attr('id');
                var self = $(button_id);
                var send_attachment_bkp = wp.media.editor.send.attachment;
                var button = $(button_id);
                var id = button.attr('id').replace('_button', '');
                _custom_media = true;
                wp.media.editor.send.attachment = function(props, attachment){
                    if ( _custom_media  ) {
                        $('.custom_media_id').val(attachment.id);
                        $('.custom_media_url').val(attachment.url);
                        $('.custom_media_image').attr('src',attachment.url).css('display','block');
                    } else {
                        return _orig_send_attachment.apply( button_id, [props, attachment] );
                    }
                };
                wp.media.editor.open(button);
                return false;
            });
        }

        if($('.custom_media_button').length){
            media_upload('.custom_media_button');
        }

        $('body').on('change', '.social_resource select', function(){
            var value = $(this).val();
            if(value == '1'){
                $(this).closest('form').find('.custom_social').hide();
            }
            else{
                $(this).closest('form').find('.custom_social').fadeIn();
            }
        });

        if ($('.set_custom_images').length > 0) {

            var file_frame;

            $('.set_custom_images').click(function( event ){
                event.preventDefault();
                $('.set_custom_images').removeClass('active');
                $(this).addClass('active');
                // If the media frame already exists, reopen it.
                if ( file_frame ) {
                    // Open frame
                    file_frame.open();
                    return;
                }
                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select a image',
                    button: {
                        text: 'Use this image',
                    },
                    multiple: false	// Set to true to allow multiple files to be selected
                });
                // When an image is selected, run a callback.
                file_frame.on( 'select', function() {
                    var attachment = file_frame.state().get('selection').first().toJSON();
                    $('.set_custom_images.active').closest('.field-custom-bg-menu').find('input').val(attachment.url);
                });
                // Finally, open the modal
                file_frame.open();
            });


        }

    })
})(jQuery);

