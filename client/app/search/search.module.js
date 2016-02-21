(function() {
  'use strict';

  angular.module('app.search', []);

  angular.module('app.search').config(function($stateProvider) {
    $stateProvider
      .state('search', {
        url: "/search",
        templateUrl: "app/search/search.html",
        controller: 'Search',
        controllerAs: 'vm',
        resolve: {
          $title: function() { return 'Search Results'; }
        }
      })
  });
})();