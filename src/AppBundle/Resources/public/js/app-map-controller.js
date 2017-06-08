app.controller("AppMapController",
    ['$scope', '$http', '$document', function($scope, $http, $document) {

        $scope.modifiedMarkers = [];
        $scope.lastSuccessfulResponse = {};
        $http.get(Routing.generate('api_bookshops')).then(
            function success(response) {
                dispatchLocationsFetchedEvent(response.data);
            }, function error() {

            }
        );

        $document[0].addEventListener('markerDragged', function(e) {
            if ($scope.auth) {
                $scope.$apply(function($scope) {
                    $scope.modifiedMarkers.push(e.data);
                });
            };
        });

        /**
         * @method submitMarkers
         */
        $scope.submitMarkers = function() {
            if ($scope.auth) {
                $http.post(Routing.generate('api_bookshops_position'), $scope.modifiedMarkers)
                    .then(function (response) {
                        $scope.modifiedMarkers = [];
                        dispatchLocationsFetchedEvent(response.data);
                    });
            }
        }

        /**
         * @method dismissNewMarkers
         */
        $scope.dismissNewMarkers = function() {
            $scope.modifiedMarkers = [];
            dispatchLocationsFetchedEvent($scope.lastSuccessfulResponse);
        }

        /**
         * @method dispatchLocationsFetchedEvent
         */
        var dispatchLocationsFetchedEvent = function(data) {
            $scope.lastSuccessfulResponse = angular.copy(data);
            $scope.auth = data.auth;
            var event = $document[0].createEvent('HTMLEvents');
            event.initEvent('locationsFetchedEvent', true, true);
            event.eventName = 'locationsFetchedEvent';
            event.data = data;

            $document[0].dispatchEvent(event);
        }
    }]
);
