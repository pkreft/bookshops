var app = angular.module('bookshops', [
]);

app
    .config(['$interpolateProvider', function($interpolateProvider) {
        $interpolateProvider.startSymbol('{[').endSymbol(']}');
    }])
    .directive('formModel', function($compile) {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                if (!scope[attrs.formModel]) {
                    scope[attrs.formModel] = {};
                }
                scope[attrs.formModel][attrs.name] = element[0].value;
                element.attr('ng-model', attrs.formModel + '.' + element[0].name);
                element.removeAttr('form-model');
                $compile(element)(scope);
            }
        };
    })
    .factory();
