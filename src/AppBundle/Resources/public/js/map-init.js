var locations = null,
    mapScriptReady = false;

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
    markerCluster,
    showClosest = false,
    addMenu;

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

        map.addListener('click', function(e) {
            if (showClosest) {
                showClosestMarkers(e.latLng);
            }
        });

        if (auth) {
            addMenu = new google.maps.InfoWindow({});
            map.addListener('rightclick', function(e) {
                var content = '<div><a onclick="addMarker(' + e.latLng.lat() + ',' + e.latLng.lng() + ');closeMenu();">Add marker here</a></div>';
                addMenu.setContent(content);
                addMenu.setPosition(e.latLng);
                addMenu.open(map);
            });
        }

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

var closeMenu = function() {
    if (addMenu) {
        addMenu.close();
    }
}

function clearMarkers() {
    markerCluster.clearMarkers();
    markers = [];
}

function refreshMarkers() {
    markerCluster.clearMarkers();
    markerCluster.addMarkers(markers);
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

function addMarker(lat, lng) {
    var data = {
        lat : lat,
        lng : lng,
    };
    dispatchEvent('addMarker', data);
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

var circle,
    km = 5 * 1000;
function showClosestMarkers(referencePosition) {
    if (circle) {
        circle.setMap(null);
        circle = null;
    }
    refreshMarkers();

    circle = new google.maps.Circle({
        center: referencePosition,
        radius: km,
        clickable: false,
        map: map,
        fillOpacity: 0.1,
        strokeWeight: 1,
    });

    // if (circle) {
    //     map.fitBounds(circle.getBounds());
    // }
    angular.forEach(markers, function (marker) {
        var distance_from_location = google.maps.geometry.spherical.computeDistanceBetween(referencePosition, marker.position);
        if (distance_from_location > km) {
            markerCluster.removeMarker(marker);
        }
    });
}

function radius(button) {
    if (showClosest) {
        button.removeClass('active');
        if (circle) {
            circle.setMap(null);
        }
        refreshMarkers();
        showClosest = false;
    } else {
        if (km) {
            button.parent().removeClass('has-error');
            button.addClass('active');
            showClosest = true;
        } else {
            button.parent().addClass('has-error');
        }
    }
}

function updateKm(newKm) {
    km = parseInt(newKm) * 1000;
}
