(function() {
  'use strict';

  angular
    .module('app.core')
    .service('User', User);

  User.$inject = ['AuthRestangular'];

  function User(AuthRestangular) {
      var scope = this;

      scope.user = null;
      scope.load = load;
      scope.get = get;

      function load () {
          AuthRestangular.one('me').get().then(function (user) {
              scope.user = user;
          })
      }

      function get() {
          return scope.user;
      }
  }
})();