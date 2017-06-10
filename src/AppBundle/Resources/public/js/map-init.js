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
var map,
    markers = [],
    popupTemplate = angular.element(document).find('pre').html(),
    markerCluster;
function initMap() {
    if (locations && mapScriptReady) {
        if (!map) {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 10,
                center: {lat: 54.3610873, lng: 18.6900271}
            });
        } else {
            clearMarkers();
        }
        var auth = locations.auth;
        delete locations.auth;

        angular.forEach(locations, function(location) {
            var infoWindow = new google.maps.InfoWindow({
                content: renderTemplate(location)
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
                infoWindow.open(map, marker);
            });

            markers.push(marker);
        });

        markerCluster = new MarkerClusterer(map, markers,
            // {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
            {imagePath: '/assets/images/markerclusterer/m'});
    }
}

function clearMarkers() {
    markerCluster.clearMarkers();
    markers = [];
}

function renderTemplate(location) {
    var template = popupTemplate
        .replace('[%name]', location.name)
        .replace('[%marker]', location.id)
        .replace('[%open_at]', location.open_hour + ' - ' + location.close_hour);
    var books = '';
    angular.forEach(location.books, function(book) {
        books += '<div> - ' + book.title + '</div>';
    });

    return template.replace('[%books]', books);
}

function dispatchMarkerDraggedEvent(e, id) {
    var data = {
        lat : e.latLng.lat(),
        lng : e.latLng.lng(),
        id : id,
    };
    dispatchEvent('markerDragged', data);
}

function more(locationId) {
    dispatchEvent('showModal', locationId);
}

function dispatchEvent(name, data) {
    var event = document.createEvent('HTMLEvents');
    event.initEvent(name, true, true);
    event.eventName = name;
    event.data = data;

    document.dispatchEvent(event);
}
