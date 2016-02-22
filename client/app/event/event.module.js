(function() {
  'use strict';

  angular.module('app.event', ['app.profile']);

  angular.module('app.event').config(function($stateProvider) {
    $stateProvider
      .state('event', {
        url: "/event/{eventId:int}",
        templateUrl: "app/event/event.html",
        controller: 'Event',
        controllerAs: 'vm',
        resolve: {
          $title: function() { return 'Event Details'; }
        }
      })
  });
})();