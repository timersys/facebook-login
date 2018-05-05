window.FBL = {};
function fbl_loginCheck() {
    window.FB.getLoginStatus( function(response) {
        FBL.handleResponse(response);
    } );
}
(function( $ ) {
	'use strict';
	FBL.renderFinish = function () {
        $('.fbl-spinner').hide();
    }

    FBL.handleResponse = function( response ) {
        var button          =  $('.fbl-button'),
            $form_obj       = button.parents('form') || false,
            $redirect_to    = $form_obj.find('input[name="redirect_to"]').val() || button.data('redirect'),
            running         = false;
        /**
         * If we get a successful authorization response we handle it
         */
        if (response.status == 'connected' && ! running) {
            running = true;
            var fb_response = response;
            $('.fbl-spinner').fadeIn();
            $('.fb-login-button').hide();

            /**
             * Send the obtained token to server side for extra checks and user data retrieval
             */
            $.ajax({
                data: {
                    action: "fbl_facebook_login",
                    fb_response: fb_response,
                    security: button.data('fb_nonce')
                },
                global: false,
                type: "POST",
                url: fbl.ajaxurl,
                success: function (data) {
                    if (data && data.success) {
                        if( data.redirect && data.redirect.length ) {
                            location.href = data.redirect;
                        } else if ( $redirect_to.length ) {
                            location.href = $redirect_to;
                        } else {
                            location.href = fbl.site_url;
                        }
                    } else if (data && data.error) {
                        $('.fbl-spinner').hide();
                        $('.fb-login-button').show();

                        if ($form_obj.length) {
                            $form_obj.append('<p class="fbl_error">' + data.error + '</p>');
                        } else {
                            // we just have a button
                            $('<p class="fbl_error">' + data.error + '</p>').insertAfter(button);
                        }
                        // if we had any error remove user from app so we request again permissions
                        FB.api(
                            "/"+data.fb.id+"/permissions",
                            "DELETE",
                            function (response) {
                                if (response && !response.error) {
                                }
                            }
                        );
                    }
                },
                error: function (data) {
                    $('.fbl-spinner').hide();
                    $('.fb-login-button').show();
                    $form_obj.append('<p class="fbl_error">' + data + '</p>');
                }
            });

        } else {
            button.removeClass('fbl-loading');
            if( navigator.userAgent.match('CriOS') )
                window.open('https://www.facebook.com/dialog/oauth?client_id=' + fbl.appId + '&redirect_uri=' + document.location.href + '&scope='+fbl.scopes, '', null);

            if( "standalone" in navigator && navigator.standalone )
                window.location.assign('https://www.facebook.com/dialog/oauth?client_id=' + fbl.appId + '&redirect_uri=' + document.location.href + '&scope='+fbl.scopes);
        }
    };
})( jQuery );
