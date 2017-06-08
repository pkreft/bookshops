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
        popupTemplate = angular.element(document).find('pre').html();

        var markers = [],
            template;
        angular.forEach(locations, function(location) {
            template = popupTemplate.replace('[%name]', location.name)
            var books = '';
            angular.forEach(location.books, function(book) {
               books += '<div> - ' + book.title + '</div>';
            });
            template = template.replace('[%books]', books)
            var infowindow = new google.maps.InfoWindow({
                content: template
            });

            var marker = new google.maps.Marker({
                position: location,
            });

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
