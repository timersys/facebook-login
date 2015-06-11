(function( $ ) {
	'use strict';
    // Thanks to Zane === zM Ajax Login & Register === for this bit
    $( document ).on( 'click', '.js-fbl', function( e ) {
        e.preventDefault();
        var $form_obj       = $( this ).parents('form'),
            $this           = $( this),
            $redirect_to    = $form_obj.find('input[name="redirect_to"]').val();
        $this.addClass('loading');
        FB.login( function( response ) {
            /**
             * If we get a successful authorization response we handle it
             * note the "scope" parameter.
             */
            var requested_scopes = ['public_profile','email','contact_email'];
            var response_scopes = $.map( response.authResponse.grantedScopes.split(","), $.trim );
            var diff = $( requested_scopes ).not( response_scopes ).get();
            var granted_access = diff.length;
            if ( ! granted_access ){

                /**
                 * "me" refers to the current FB user, console.log( response )
                 * for a full list.
                 */
                FB.api('/me', function(response) {
                    var fb_response = response;

                    /**
                     * Make an Ajax request to the "facebook_login" function
                     * passing the params: username, fb_id and email.
                     *
                     * @note Not all users have user names, but all have email
                     * @note Must set global to false to prevent gloabl ajax methods
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
                            }
                        }
                    });
                });
            } else {
                $this.removeClass('loading');
                console.log('User canceled login or did not fully authorize.');
            }
        },{
            /**
             * See the following for full list:
             * @url https://developers.facebook.com/docs/authentication/permissions/
             */
            scope: 'email',
            return_scopes: true
        });
    });

})( jQuery );
