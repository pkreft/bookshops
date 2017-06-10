var app = angular.module('bookshops', [
    'ngDialog',
    'ui-notification',
]);

app
    .config(['$interpolateProvider', function($interpolateProvider) {
        $interpolateProvider.startSymbol('{[').endSymbol(']}');
    }]);
