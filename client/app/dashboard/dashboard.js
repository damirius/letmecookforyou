(function() {
    'use strict';

    angular
        .module('app.dashboard')
        .controller('Dashboard', Dashboard);

    Dashboard.$inject = ['$rootScope'];

    function Dashboard($rootScope) {

        /*jshint validthis: true */
        var vm = this;

        vm.events = [];
        Restangular.getList('events').then(function (events) {
            vm.events = events;
        })
    }
})();