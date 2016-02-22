(function() {
  'use strict';

  angular.module('app.profile', ['app.event']);

  angular.module('app.profile').config(function($stateProvider) {
    $stateProvider
      .state('profile', {
        url: "/profile",
        templateUrl: "app/profile/profile.html",
        controller: 'Profile',
        controllerAs: 'vm',
        resolve: {
          $title: function() { return 'Profile'; }
        }
      })
  });
})();