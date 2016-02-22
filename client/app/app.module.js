(function() {
    'use strict';

    angular.module('app', [
        'ui.router',
        'ui.router.title',
        'restangular',
        'ngStorage',

        'app.core',
        'app.navbar',
        'app.dashboard',
        'app.search',
        'app.profile'
    ]);

})();