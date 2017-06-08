var locations = null,
    mapScriptReady = false

document.addEventListener('locationsFetchedEvent', function(event) {
    locations = event.data;
    initMap();
});

//callback for google.maps script
function mapsLoaded() {
    mapScriptReady = true;
    initMap()
}

function initMap() {
    if (locations && mapScriptReady) {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
            center: {lat: 54.3610873, lng: 18.6900271}
        }),
        popupTemplate = angular.element(document).find('pre').html(),
        auth = locations.auth;
        delete locations.auth;

        var markers = [],
            template;
        angular.forEach(locations, function(location) {
            template = popupTemplate.replace('{[name]}', location.name)
            var books = '';
            angular.forEach(location.books, function(book) {
               books += '<div> - ' + book.title + '</div>';
            });
            template = template.replace('{[books]}', books)
            var infowindow = new google.maps.InfoWindow({
                content: template
            });

            var marker = new google.maps.Marker({
                id: location.id,
                position: location,
                draggable: auth ? true : false
            });
            if (auth) {
                marker.addListener('dragend', function(e) {
                    // this.setIcon('http://maps.gstatic.com/mapfiles/markers2/icon_green.png');
                    this.setIcon('https://mt.google.com/vt/icon?psize=20&font=fonts/Roboto-Regular.ttf&color=ff330000&name=icons/spotlight/spotlight-waypoint-a.png&ax=44&ay=48&scale=1&text=%E2%80%A2');
                    dispatchMarkerDraggedEvent(e, this.id);
                });
            }

            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });

            markers.push(marker);
        });

        var markerCluster = new MarkerClusterer(map, markers,
            // {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
            {imagePath: '/assets/images/markerclusterer/m'});
    }
}

function dispatchMarkerDraggedEvent(e, id) {
    var event = document.createEvent('HTMLEvents');
    event.initEvent('markerDragged', true, true);
    event.eventName = 'markerDragged';
    event.data = {
        lat : e.latLng.lat(),
        lng : e.latLng.lng(),
        id : id,
    };

    document.dispatchEvent(event);
}
