/**
 * jQuery App the consumes the RMaps REST endpoint for lat./long. and basic post data
 *
 * Response from the get_pins endpoint looks like this: ['ID', 'title', 'infowindow', 'see_more', 'permalink', 'lat', 'lon', 'current']
 */
;var RMaps = (function($){
    var rmap = document.getElementById('rmap'),
        $rmap = $(rmap),
        pins = {},
        api_settings = {
            api_base: '',
            endpoints: {
                pins: {route: 'get-pins/', method: 'GET'},
                api_key: {route: 'get-api-key/', method: 'GET'}
            }
        },
        maps_api_key,
        map, bounds, infoWindow;

    var init = function(){

        /** Get the API route without any unecessary markup */
        var getUrl = window.location,
            baseUrl = getUrl.protocol + "//" + getUrl.host;

        api_settings.api_base = baseUrl + '/wp-json/restful-maps/v1/';

        get_api_key();
    };

    var get_api_key = function(){
        var api_key = '';

        do_ajax(api_settings.endpoints.api_key, {})
            .done(function(data) {
                maps_api_key = data;
                init_rmaps();
            });
    };

    var get_pins = function(callback) {
        do_ajax(api_settings.endpoints.pins, {} )
            .done(function(data) {
                pins = data;
                if ('function' === typeof callback) {
                    callback.call();
                }
            });
    };

    var init_rmaps = function() {
        bounds = new google.maps.LatLngBounds();
        infoWindow = new google.maps.InfoWindow();

        $rmap.empty();

        map = new google.maps.Map(rmap, {
            zoom: 4,
            center: new google.maps.LatLng(16.7758, 3.0094),
            scrollwheel: false,
            mapTypeId: google.maps.MapTypeId.SATELLITE
        });

        get_pins(render_pin);

    };

    var render_pin = function(){

        var color = "9A9A9A",
            marker, i;

        $.each(pins, function(i, pin){

            /** Color the current pin green */
            if(pin.ID === pin.current)
                color = '34BA46';

            var pinImg = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + color,
                new google.maps.Size(21, 34),
                new google.maps.Point(0,0),
                new google.maps.Point(10, 34));

            marker = new google.maps.Marker({
                position: new google.maps.LatLng(pin.lat, pin.lon),
                map: map,
                icon: pinImg
            });

            /** Set the infowindow content */
            var iwContent = "<h1 class='rm-title'>" + pin.title + "</h1>" +
                "<p class='rm-body'>" + pin.infowindow + "</p>" +
                "<a href='" + pin.permalink + "' alt='" + pin.title + "' class='rm-see-more'>" + pin.see_more + "</a>";

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infoWindow.setContent(iwContent);
                    infoWindow.open(map, marker);
                }
            })(marker, i));

            /** Extend the map window bounds so all markers are visible */
            bounds.extend(marker.position);
            map.fitBounds(bounds);
        });
    };

    var do_ajax = function(endpoint, data) {
        return $.ajax({
            url: api_settings.api_base + endpoint.route,
            method: endpoint.method,
            data: data
        });
    };

    /** Public API */
    return {
        init: init
    };
})(jQuery);


jQuery(document).ready( function() {
    RMaps.init();
});
