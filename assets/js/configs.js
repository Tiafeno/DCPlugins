/**
 * Created by Tiafeno on 23/08/2017.
 */
(function($){
  $( document ).ready(function(){
    $( 'input[type*="submit"]')
      .on('click', function(){
        var idType = $( this ).data('form');
        var spinner = $("form#" + idType + " .uk-spinner");
        var log = $("form#" + idType + " #log");
        var post_type = $( "form#" + idType + " input[name*='post_type']" );
        var page_id = $( "form#" + idType + " select[name*='page_id']" );
        // loading...
        spinner.toggleClass('uk-hidden');
        var xhr = $.ajax({
          url: configs.ajax,
          method: "POST",
          data: {
            action : 'action_save_configs',
            postType : post_type.val(),
            pageID: page_id.val()
          },
          dataType: "json"
        });

        xhr.done(function( data ) {
          spinner.toggleClass('uk-hidden');
          if (data.type == 'success'){
            log.text('Success')
              .removeClass('uk-label-warning')
              .addClass('uk-label-success')
              .toggleClass('uk-hidden');

            window.setTimeout(function(){
              log.toggleClass('uk-hidden');
            }, 2000);
          }
        });

        xhr.fail(function( jqXHR, textStatus ) {
          spinner.toggleClass('uk-hidden');
          log.text('Error')
            .removeClass('uk-label-success')
            .addClass('uk-label-warning')
            .toggleClass('uk-hidden');
          window.setTimeout(function(){
            log.toggleClass('uk-hidden');
          }, 8000);
          alert( "Request failed: " + textStatus );
        });
      });

  });


})(jQuery);