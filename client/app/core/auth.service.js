(function () {
    'use strict';

    angular
        .module('app.core')
        .service('Auth', Auth);

    Auth.$inject = ['Restangular', '$localStorage', '$window'];

    function Auth(Restangular, $localStorage, $window) {

        this.isLoggedIn = isLoggedIn;
        this.login = login;
        this.logout = logout;

        function login(username, password) {
            var params = {
                _username: username || '',
                _password: password || ''
            }

            return Restangular.oneUrl('apiToken', '/api/login').post(null, params).then(function (data) {
                $localStorage.authToken = data.token;
                $window.location.reload();
            });
        };

        function logout() {
            delete $localStorage.authToken;
        }

        function isLoggedIn() {
            return $localStorage.authToken != undefined;
        }
    }
})();