/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
google.maps.visualRefresh = true;
(function ($) {
    $.fn.iwMapField = function (options) {
        $(this).each(function () {
            if ($(this).hasClass('map-rendered')) {
                return;
            }
            $(this).addClass('map-rendered');
            var iwMapField = this;
            this.map = null;
            //this.marker = null;
            //this.geocoder = new google.maps.Geocoder();


            this.renderMap = function () {
                var mapObj = this;
                mapObj.map = new google.maps.Map($(this).find('.map-preview').get(0),options);

                // Create the search box and link it to the UI element.
                var input = document.getElementById('map-location');
                console.log(input);
                var searchBox = new google.maps.places.SearchBox(input);
//                mapObj.map.controls[google.maps.ControlPosition.TOP_RIGHT].push(input);

                // Bias the SearchBox results towards current map's viewport.
                mapObj.map.addListener('bounds_changed', function () {
                    searchBox.setBounds(mapObj.map.getBounds());
                });

                // Listen for the event fired when the user selects a prediction and retrieve
                // more details for that place.
                searchBox.addListener('places_changed', function () {
                    var places = searchBox.getPlaces();

                    if (places.length == 0) {
                        return;
                    }


                    // For each place, get the icon, name and location.
                    var bounds = new google.maps.LatLngBounds();
                    places.forEach(function (place) {
                        if (!place.geometry) {
                            console.log("Returned place contains no geometry");
                            return;
                        }

                        if (place.geometry.viewport) {
                            // Only geocodes have viewport.
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                    });
                    mapObj.map.fitBounds(bounds);
                    mapObj.marker.setPosition(mapObj.map.getCenter());
                });

                mapObj.marker = new google.maps.Marker({
                    map: mapObj.map,
                    position: mapObj.map.getCenter(),
                    animation: google.maps.Animation.DROP,
                    draggable: true
                });
                google.maps.event.addListener(this.marker, 'dragend', function () {
                    var latlng = mapObj.marker.getPosition();
                    mapObj.map.setCenter(latlng);
                    mapObj.billDataToForm(latlng);
                });
                google.maps.event.addListener(this.map, 'click', function (res) {
                    var latlng = new google.maps.LatLng(res.latLng.lat(), res.latLng.lng());
                    mapObj.marker.setPosition(latlng);
                    mapObj.map.setCenter(latlng);
                    mapObj.billDataToForm();
                });
                google.maps.event.addListener(this.map, 'zoom_changed', function () {
                    mapObj.billDataToForm();
                });
            };

            this.billDataToForm = function () {
                var center = this.map.getCenter(),
                        zoomlv = this.map.getZoom(),
                        data = {zoomlv: zoomlv, lat: center.lat(), lng: center.lng()};
                $('.iw-map-field input.iw-map').val(JSON.stringify(data));
            };

            this.renderMap();
        });
    };

$(document).ready(function(){
    $('.iw-map-field').each(function(){
        var map_options = $(this).data('map-options');
        map_options.center = new google.maps.LatLng(map_options.center.lat,map_options.center.lng);
        map_options.mapTypeId= google.maps.MapTypeId.ROADMAP;
        $(this).iwMapField(map_options);
    });
});
})(jQuery);