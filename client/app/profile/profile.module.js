(function() {
  'use strict';

  angular.module('app.profile', ['app.event', 'app.dashboard']);

  angular.module('app.profile').config(function($stateProvider) {
    var tokenListener;
    $stateProvider
      .state('profile', {
        url: "/profile",
        templateUrl: "app/profile/profile.html",
        controller: 'Profile',
        controllerAs: 'vm',
        resolve: {
          $title: function() { return 'Profile'; }
        },
        onEnter: function($rootScope, $localStorage, $state){
            tokenListener = $rootScope.$watch(function () { return $localStorage.authToken }, function (token) {
                if (!token) {
                    $state.go('dashboard');
                }
            })
        },
        onExit: function() {
            tokenListener();
        }
      })
  });
})();