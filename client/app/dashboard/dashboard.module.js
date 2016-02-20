(function() {
    'use strict';

    angular.module('app.dashboard', []);

    angular.module('app.dashboard').config(function($stateProvider) {
        $stateProvider
            .state('dashboard', {
                url: "/",
                templateUrl: "app/dashboard/dashboard.html",
                controller: 'Dashboard',
                controllerAs: 'vm',
                resolve: {
                    $title: function() { return 'Dashboard'; }
                }
            })
    });
})();