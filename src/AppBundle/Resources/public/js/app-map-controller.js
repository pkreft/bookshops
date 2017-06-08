app.controller("AppMapController",
    ['$scope', '$http', '$document', function($scope, $http, $document) {

        $http.get(Routing.generate('api_bookshops')).then(
            function success(response) {
                var event = $document[0].createEvent('HTMLEvents');
                event.initEvent('locationsFetchedEvent', true, true);
                event.eventName = 'locationsFetchedEvent';
                event.data = response.data;
                $document[0].dispatchEvent(event);
            }, function error() {

            }
        );
    }]
);
