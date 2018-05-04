(function ($) {
    "use strict";
// When the window has finished loading create our google map below
    $.fn.iwMap = function () {
        $(this).each(function () {
            var self = $(this),
                map_style = $(this).data('map_style'),
                address = $(this).data('address'),
                phone_number = $(this).data('phone_number'),
                email_address = $(this).data('email_address'),
                website_url = $(this).data('website_url'),
                icon = $(this).data('marker_icon'),
                image = $(this).data('image'),
                lat = $(this).data('lat'),
                long = $(this).data('long'),
                width = $(this).data('width'),
                height = $(this).data('height'),
                info_panby_x = $(this).data('info_panby_x'),
                info_panby_y = $(this).data('info_panby_y'),
                panby_x = $(this).data('panby_x'),
                panby_y = $(this).data('panby_y');
            var loc = new google.maps.LatLng(lat, long);
            //$(this).addClass('map-rendered');

            var mapOptions = {
                scrollwheel: false,
                //disableDoubleClickZoom: true,
                draggable: true,
                // How zoomed in you want the map to start at (always required)
                zoom: $(this).data('zoom'),
                // The latitude and longitude to center the map (always required)
                center: loc,
                // How you would like to style the map.
                // This is where you would paste any style found on Snazzy Maps.
                styles:
                    [
                        {"featureType": "landscape", "elementType": "labels", "stylers": [{"color": "#fbfbfb"},{"visibility": "off"}]},
                        {"featureType": "transit", "elementType": "labels", "stylers": [{"visibility": "off"}]},
                        {"featureType": "poi", "elementType": "labels", "stylers": [{"visibility": "off"}]},
                        {"featureType": "water", "elementType": "labels", "stylers": [{"color": "#f6f6f6"},{"visibility": "off"}]},
                        {"featureType": "road", "elementType": "labels.icon", "stylers": [{"visibility": "off"}]},
                        {"stylers": [{"hue": "#00aaff"}, {"saturation": -100}, {"gamma": 2.15}, {"lightness": 12}]},
                        {"featureType": "road", "elementType": "labels.text.fill", "stylers": [{"color": "#d3d3d3"},{"visibility": "on"},
                            {"lightness": 24}]}, {"featureType": "road", "elementType": "geometry", "stylers": [{"lightness": 57}]}
                    ]

            };

            if(map_style == 'style2'){
                var mapOptions = {
                    scrollwheel: false,
                    //disableDoubleClickZoom: true,
                    draggable: true,
                    // How zoomed in you want the map to start at (always required)
                    zoom: $(this).data('zoom'),
                    // The latitude and longitude to center the map (always required)
                    center: loc,
                    // How you would like to style the map.
                    // This is where you would paste any style found on Snazzy Maps.
                    styles: [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}]
                };
            }

            var map = new google.maps.Map($(this).find('.map-view').get(0), mapOptions);

            if(panby_x != '' || panby_y != ''){
                map.panBy(panby_x, panby_y);
            }
            var marker_options = {
                position: loc,
                map: map
            };

            if(address){
                marker_options.address = address;
            }

            if(phone_number){
                marker_options.phone_number = phone_number;
            }

            if(email_address){
                marker_options.email_address = email_address;
            }

            if(website_url){
                marker_options.website_url = website_url;
            }
            if(icon){
                marker_options.icon = icon;
                //marker_options.icon.url = icon;
                //marker_options.icon.anchor = new google.maps.Point(26, -160);
            }

            var marker = new google.maps.Marker(marker_options);

            if(image){
                var content = '';
                if(image){
                    content += '<div class="info-img"><img src="'+image+'" alt=""></div>';
                }
                if(address || phone_number || email_address || website_url){
                    content += '<div class="info theme-color">';
                    if(address){
                        content += '<div class="address"><i class="fa fa-map-marker"></i>'+address+'</div>';
                    }
                    if(phone_number){
                        content += '<div><i class="fa fa-phone"></i>'+phone_number+'</div>';
                    }
                    if(email_address){
                        content += '<div><i class="fa fa-envelope-o"></i>'+email_address+'</div>';
                    }
                    if(website_url){
                        content += '<div><i class="fa fa-globe"></i>'+website_url+'</div>';
                    }
                    content += '</div>';
                }

                var infobox = new InfoBox({
                    content: content,
                    disableAutoPan: false,
                    maxWidth: 150,
//                    pixelOffset: new google.maps.Size(-230, -450),
                    pixelOffset: new google.maps.Size(0 - (width/2) + info_panby_x, 0 - height - info_panby_y),
                    zIndex: null,
                    boxStyle: {
                        opacity: 1,
                        width: width+'px',
                        left: '50%'
                    },
                    closeBoxMargin: "0 0 -15px 0",
                    closeBoxURL: "https://www.google.com/intl/en_us/mapfiles/close.gif",
                    infoBoxClearance: new google.maps.Size(1, 1)
                });
                //map.panBy(0, -160);
                infobox.open(map, marker);
                google.maps.event.addListener(marker, 'click', function () {
                    infobox.open(map, this);
                    //map.panTo(loc);
                });
            }
            else
            {
                var markerinfowindow = new google.maps.InfoWindow();
                markerinfowindow.setContent(address);
                //map.panBy(-300, 0);
                google.maps.event.addListener(marker, 'click', function () {
                    markerinfowindow.open(map, marker);
                });
            }

            self.data('map', map);
            self.data('marker', marker);
            self.data('markerinfowindow', markerinfowindow);
            //map, maker

            //marker change position
            //map center setCenter
            //new google.maps.LatLng(-34, 151)
        });
    };
    $(window).load(function(){
        $(".map-contain").iwMap();
        $(".inwave-multi-map .iw-map-item").on("click", function(){
            var itemClick = $(this);
            $('.inwave-multi-map .iw-map-item.active').removeClass('active');
            itemClick.addClass('active');
            var parent = itemClick.closest('.inwave-multi-map');
            var map_container = parent.find(".map-contain");
            var map = map_container.data('map');
            var marker = map_container.data('marker');
            var markerinfowindow = map_container.data('markerinfowindow');
            var new_lat = itemClick.data( "lat" );
            var new_long = itemClick.data( "long" );
            var new_address = itemClick.data( "address" );
            var latlng = new google.maps.LatLng(new_lat, new_long);
            setTimeout( function(){
                map.setCenter(latlng);
                marker.setPosition(latlng);
                markerinfowindow.setContent(new_address);
            }, 1000 );
        });

    });
})(jQuery);

