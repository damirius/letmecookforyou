(function() {
    'use strict';

    angular
        .module('app.core')
        .config(RouterConfig);

    RouterConfig.$inject = ['$urlRouterProvider'];

    function RouterConfig($urlRouterProvider) {
        $urlRouterProvider.otherwise("/");
    }
})();