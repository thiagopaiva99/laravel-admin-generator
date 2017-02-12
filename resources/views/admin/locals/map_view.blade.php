<div id="map-scripts"></div>
<script type="text/javascript">
    function initialize() {

        var marker = null;
        var lat_lng;
        $.ajax({
            url  : window.location.protocol + '//' + window.location.host + '/get-ip-location',
            type : 'GET',
            success: function(data){
                lat_lng = data;

                var myCenter = new google.maps.LatLng(lat_lng.latitude, lat_lng.longitude);
                var mapOptions = {
                    center: myCenter,
                    zoom: 11,
                    zoomControl: true,
                    mapTypeControl: false,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };

                var map = new google.maps.Map(document.getElementById("map"), mapOptions);

                var lat = jQuery('input[name=lat]').val();
                var lng = jQuery('input[name=lng]').val();
                if (lat != null && lng != null && lat != '' && lng != '') {
                    var newCenter = new google.maps.LatLng(lat, lng);

                    map.setCenter(newCenter);
                    marker = new google.maps.Marker({position: newCenter, map: map});
                }

                google.maps.event.addListener(map, "click", function (event) {

                    if (marker != null) marker.setMap(null);
                    marker = new google.maps.Marker({position: event.latLng, map: map});

                    jQuery('input[name=lat]').val(event.latLng.lat());
                    jQuery('input[name=lng]').val(event.latLng.lng());

                    jQuery.ajax({
                        url: 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + event.latLng.lat() + ',' + event.latLng.lng(),
                        dataType: 'json',
                        success: function (data) {
                            jQuery('#address').val(data.results[0].formatted_address);
                        },
                        error: function () {
                            jQuery('#address').val('Não encontrado');
                        }
                    });

                });

                jQuery("#address").focusout(function(event){
                    event.preventDefault();

                    jQuery.ajax({
                        url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + jQuery(this).val(),
                        dataType: 'json',
                        success: function (data) {

                            var location = data.results[0].geometry.location;
                            var position = new google.maps.LatLng(location.lat, location.lng);

                            if (marker != null) marker.setMap(null);
                            marker = new google.maps.Marker({position: position, map: map});

                            jQuery('input[name=lat]').val(location.lat);
                            jQuery('input[name=lng]').val(location.lng);
                            map.setCenter(position);
                        },
                        error: function () {
                            if (marker != null) marker.setMap(null);
                        }
                    });
                });

                jQuery(".btn-search-map").on('click', function(event){
                    event.preventDefault();

                    $(".btn-search-map").addClass("m-progress").attr({"disabled" : "disabled"});

                    jQuery.ajax({
                        url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + jQuery("#address").val(),
                        dataType: 'json',
                        success: function (data) {

                            var location = data.results[0].geometry.location;
                            var position = new google.maps.LatLng(location.lat, location.lng);

                            if (marker != null) marker.setMap(null);
                            marker = new google.maps.Marker({position: position, map: map});

                            jQuery('input[name=lat]').val(location.lat);
                            jQuery('input[name=lng]').val(location.lng);
                            map.setCenter(position);

                            $(".btn-search-map").removeClass("m-progress").removeAttr("disabled");
                        },
                        error: function () {
                            if (marker != null) marker.setMap(null);
                        }
                    });
                });

                google.maps.event.addDomListener(document.getElementById("address"), 'keydown', function (event) {

                    if (event.keyCode == 13) {

                        event.preventDefault();

                        jQuery.ajax({
                            url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + jQuery(this).val(),
                            dataType: 'json',
                            success: function (data) {

                                var location = data.results[0].geometry.location;
                                var position = new google.maps.LatLng(location.lat, location.lng);

                                if (marker != null) marker.setMap(null);
                                marker = new google.maps.Marker({position: position, map: map});

                                jQuery('input[name=lat]').val(location.lat);
                                jQuery('input[name=lng]').val(location.lng);
                                map.setCenter(position);
                            },
                            error: function () {
                                if (marker != null) marker.setMap(null);
                            }
                        });

                    }

                });
            }
        });

    }

    function loadScript(src, callback) {

        var script = document.createElement('script');
        script.type = 'text/javascript';

        if (callback) script.onload = callback;

        document.getElementById('map-scripts').appendChild(script);
        script.src = src;

    }

    loadScript('https://maps.googleapis.com/maps/api/js?v=3.exp&callback=initialize&key=AIzaSyBDmfcFhqs-rXv7_kyYPX1QfFqrETWLg94', function () {
    });
</script>
