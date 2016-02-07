/** jQuery App the consumes the RMaps REST endpoint for lat./long. and basic post data */
;var RMaps = (function($){
    var $rmaps = $(document.getElementsByClassName('rmaps')),
        pins = {},
        api_settings = {
            api_base: '',
            endpoints: {
                pins: {route: 'get-pins/', method: 'GET'},
            }
        };

    var init = function(){

        /** Get the API route without any unecessary markup */
        var getUrl = window.location,
            baseUrl = getUrl.protocol + "//" + getUrl.host;

        api_settings.api_base = baseUrl + '/wp-json/restful-maps/v1/';

        init_rmaps();

    };

    var get_pins = function(callback) {
        do_ajax(api_settings.endpoints.pins, {} )
            .done(function(data) {
                pins = data;
                if ('function' === typeof callback) {
                    callback.call();
                }
            } );
    };

    var init_rmaps = function() {
        $rmaps.empty();
        /*$.each( getLS( 'readingList' ), function( i, ID ) {
            $.each( posts, function( i, post ) {
                if ( post.ID === ID ) {
                    addReadingListElem( post );
                }
            } );
        } );*/

        get_pins(render_pin);
    };

    var render_pin = function(){
        $.each(pins, function(i, pin){
            console.log('This lat: ' + pin.lat + '; This lon: ' + pin.lon );
        });
    };

    var do_ajax = function(endpoint, data) {
        return $.ajax( {
            url: api_settings.api_base + endpoint.route,
            method: endpoint.method,
            data: data
        } );
    };

    /** Public API */
    return {
        init: init
    };
})(jQuery);


jQuery(document).ready( function() {
    RMaps.init();
});
