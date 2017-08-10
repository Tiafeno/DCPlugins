(function ($) {
  $(document).ready(function () {
    var file_frame;
    var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
    $( '#upload_image_button' ).on('click', function ( event ) {
      event.preventDefault();
      // Create the media frame.
      file_frame = wp.media.frames.file_frame = wp.media({
        title: 'Select a image to upload',
        button: {
          text: 'Use this image',
        },
        multiple: false	// Set to true to allow multiple files to be selected
      });
      // When an image is selected, run a callback.
      file_frame.on('select', function () {
        attachment = file_frame.state().get( 'selection' ).first().toJSON();
        $( '#image-preview' ).attr('src', attachment.url).css('width', 'auto');
        $( '#image_attachment_id' ).val(attachment.id);
        wp.media.model.settings.post.id = wp_media_post_id;
      });
      file_frame.open();
    });
    $( 'a.add_media' ).on('click', function () {
      wp.media.model.settings.post.id = wp_media_post_id;
    });
  });
})(jQuery)
