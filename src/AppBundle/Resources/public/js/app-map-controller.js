app.controller("AppMapController",
    ['$scope', '$http', '$document', '$interval', 'ngDialog',
        function($scope, $http, $document, $interval, ngDialog) {

        $scope.refreshing = true;
        $scope.modifiedMarkers = [];
        $scope.lastSuccessfulResponse = {};
        $http.get(Routing.generate('api_bookshops')).then(
            function success(response) {
                dispatchLocationsFetchedEvent(response.data);
                $scope.refreshing = false;
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

        $document[0].addEventListener('showModal', function(e) {
            $scope.refreshing = true;
            $http.get(Routing.generate('api_bookshops', {id: e.data})).then(
                function success(response) {
                    $scope.refreshing = false;
                    ngDialog.open({
                        template: '/assets/templates/bookshop-modal.html',
                        className: 'ngdialog-theme-default',
                        data: {
                            location: response.data[0]
                        }
                    });
                }, function error() {

                }
            );
        });

        /**
         * @method submitMarkers
         */
        $scope.submitMarkers = function() {
            if ($scope.auth) {
                $scope.refreshing = true;
                $http.post(Routing.generate('api_bookshops_position'), $scope.modifiedMarkers)
                    .then(function (response) {
                        $scope.modifiedMarkers = [];
                        $scope.search = '';
                        dispatchLocationsFetchedEvent(response.data);
                        $scope.refreshing = false;
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
            dispatchEvent('locationsFetchedEvent', data);
        }

        $scope.typed = false;
        $scope.searching = false;
        $scope.typing = function() {
            $scope.typed = Date.now();
        }

        $interval(function () {
            if ($scope.typed && Date.now() - $scope.typed > 700) {
                if ($scope.search || $scope.search == '') {
                    $scope.searching = true;
                    $http.get(Routing.generate('api_bookshops'), {params: {
                        search : $scope.search
                    }}).then(
                        function success(response) {
                            dispatchLocationsFetchedEvent(response.data);
                            $scope.searching = false;
                        }, function error() {
                            $scope.searching = false;
                        }
                    );
                }
                $scope.typed = false;
            }
        }, 100);
    }]
);
