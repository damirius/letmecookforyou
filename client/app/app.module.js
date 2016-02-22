(function() {
    'use strict';

    angular.module('app', [
        'ui.router',
        'ui.router.title',
        'restangular',
        'ngStorage',
        'ngFileUpload',

        'app.core',
        'app.navbar',
        'app.dashboard',
        'app.search',
        'app.profile',
        'app.eventadd'
    ]);

})();