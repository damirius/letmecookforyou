(function() {
    'use strict';

    angular
        .module('app.navbar')
        .directive('navbar', navbar);


    function navbar (Auth) {
        navbar.$inject=['Auth']
        var directive = {
            link: link,
            restrict: 'E',
            scope: {

            }
        };
        return directive;

        function link(scope, element, attrs) {

        }
    }
})();