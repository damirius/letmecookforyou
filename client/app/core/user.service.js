(function() {
  'use strict';

  angular
    .module('app.core')
    .service('User', User);

  User.$inject = ['AuthRestangular', 'Auth'];

  function User(AuthRestangular, Auth) {
      var scope = this;

      scope.user = {};
      scope.get = get;

      if (Auth.isLoggedIn()) {
          scope.user = AuthRestangular.one('me').get().$object;
      }

      function get(refresh) {
          if (refresh) {
              scope.user = AuthRestangular.one('me').get().$object;
          }
          return scope.user;
      }
  }
})();