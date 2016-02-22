(function() {
    'use strict';

    angular
        .module('app.dashboard')
        .controller('Dashboard', Dashboard);

    Dashboard.$inject = ['$rootScope','AuthRestangular'];

    function Dashboard($rootScope, AuthRestangular) {

        /*jshint validthis: true */
        var vm = this;

        vm.events = [];

        AuthRestangular.all('events').getList().then(function (events) {
            vm.events = events;
        })

    }
})();