app.controller("AppMapController",
    ['$scope', '$http', '$document', '$interval', 'ngDialog', '$httpParamSerializerJQLike', 'Notification',
        function($scope, $http, $document, $interval, ngDialog, $httpParamSerializerJQLike, Notification) {

        $scope.refreshing = true;
        $scope.modifiedMarkers = [];
        $scope.lastSuccessfulResponse = {};
        $http.get(Routing.generate('api_bookshops')).then(
            function success(response) {
                dispatchLocationsFetchedEvent(response.data);
                $scope.refreshing = false;
            }, function error() {
                Notification.error('Initializing application failed.');
                $scope.refreshing = false;
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
                    $scope.refreshing = false;
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
                    .then(
                        function (response) {
                            Notification.info('Successfully updated Bookshops');
                            $scope.modifiedMarkers = [];
                            $scope.search = '';
                            dispatchLocationsFetchedEvent(response.data);
                            $scope.refreshing = false;
                        }, function () {
                            Notification.error('Adjusting positions failed.');
                            $scope.refreshing = false;
                        }
                    );
            }
        }

        /**
         * @method dismissChanges
         */
        $scope.dismissChanges = function() {
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

        var dialog;

        $document[0].addEventListener('addMarker', function(e) {
            $scope.refreshing = true;
            if ($scope.auth) {
                $http.post(Routing.generate('api_bookshops_form'), {}).then(
                    function success(response) {
                        $scope.refreshing = false;
                        $scope.form = {
                            'bookshop[_token]': response.data.csrf,
                            'bookshop[lat]': e.data.lat,
                            'bookshop[lng]': e.data.lng,
                        }
                        dialog = ngDialog.open({
                            template: '/assets/templates/bookshop-add-modal.html',
                            className: 'ngdialog-theme-default',
                            scope: $scope,
                        });
                    }, function error() {
                        $scope.refreshing = false;
                    }
                );
            }
        });

        $scope.submit = function() {
            if ($scope.auth) {
                $scope.formErrors = [];
                $scope.submiting = true;
                $http.post(
                    Routing.generate('api_bookshops_add'),
                    $httpParamSerializerJQLike($scope.form),
                    {
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        }
                    }
                ).then(
                    function (response) {
                        Notification.info('Successfully added Bookshop');
                        $scope.submiting = false;
                        if (dialog) {
                            dialog.close();
                        }
                        dispatchLocationsFetchedEvent(response.data);
                    },
                    function (response) {
                        $scope.submiting = false;
                        $scope.formErrors = response.data.errors;
                    }
                );
            }
        }

        $document[0].addEventListener('deleteMarker', function(e) {
            if ($scope.auth) {
                $scope.$apply(function($scope) {
                    $scope.modifiedMarkers.push({id: e.data});
                });
            };
        });
    }]
);
