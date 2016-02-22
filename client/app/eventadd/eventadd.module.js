(function() {
  'use strict';

  angular.module('app.eventadd',[]);

  angular.module('app.eventadd').config(function($stateProvider) {
    $stateProvider
      .state('eventadd', {
        url: "/event/add",
        templateUrl: "app/eventadd/eventadd.html",
        controller: 'EventAdd',
        controllerAs: 'vm',
        resolve: {
          $title: function() { return 'Add new Event'; }
        }
      })
  });
})();