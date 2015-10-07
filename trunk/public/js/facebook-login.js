(function( $ ) {
	'use strict';
    // Thanks to Zane === zM Ajax Login & Register === for this bit
    $( document ).on( 'click', '.js-fbl', function( e ) {
        e.preventDefault();
        var $form_obj       = $( this ).parents('form') || false,
            $this           = $( this),
            $redirect_to    = $form_obj.find('input[name="redirect_to"]').val() || false;
        $this.addClass('loading');
        $('.fbl_error').remove();

        FB.login( function( response ) {

            /**
             * If we get a successful authorization response we handle it
             */
            if (response.status == 'connected') {

                var fb_response = response;

                /**
                 * Send the obtained token to server side for extra checks and user data retrieval
                 */
                $.ajax({
                    data: {
                        action: "fbl_facebook_login",
                        fb_response: fb_response,
                        security: $this.data('fb_nonce')
                    },
                    global: false,
                    type: "POST",
                    url: fbl.ajaxurl,
                    success: function( data ){
                        if( data && data.success ) {
                            if( $redirect_to.length ) {
                                location.href = $redirect_to;
                            } else {
                                location.href = fbl.site_url;
                            }
                        } else if( data && data.error ) {
                            $this.removeClass('loading');
                            if( $form_obj.length ) {
                                $form_obj.append('<p class="fbl_error">' + data.error + '</p>');
                            } else {
                                // we just have a button
                                $('<p class="fbl_error">' + data.error + '</p>').insertAfter( $this );
                            }
                        }
                    },
                    error: function( data ){
                        $this.removeClass('loading');
                        $form_obj.append( '<p class="fbl_error">' + data + '</p>' );
                    }
                });

            } else {
                $this.removeClass('loading');
                console.log('User canceled login or did not fully authorize.');
            }
        },{
            scope: fbl.scopes,
            return_scopes: true,
            auth_type: 'rerequest'
        });
    });

})( jQuery );
