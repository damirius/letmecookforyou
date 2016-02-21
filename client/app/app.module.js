(function() {
    'use strict';

    angular.module('app', [
        'ui.router',
        'ui.router.title',
        'restangular',

        'app.core',
        'app.dashboard'
    ]);

})();