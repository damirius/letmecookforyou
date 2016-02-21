(function() {
  'use strict';

  angular
    .module('app.core')
    .service('Auth', Auth);

  Auth.$inject = ['Restangular', '$localStorage'];

  function Auth(Restangular, $localStorage ) {

    this.isLoggedIn = isLoggedIn;
    this.login = login;
    this.logout = logout;

    function login (username, password) {
      var params = {
        _username: username || '',
        _password: password || ''
      }

      return Restangular.oneUrl('apiToken', '/api/login').post(params).then(function (data) {
        $localStorage.authToken = data.token;
      });
    };

    function logout () {
      delete $localStorage.authToken;
    }

    function isLoggedIn() {
      return $localStorage.authToken != undefined;
    }
  }
})();