(function() {
    'use strict';

    angular
        .module('app.core')
        .config(RouterConfig)
        .factory('AuthRestangular',AuthRestangular);

    RouterConfig.$inject = ['$urlRouterProvider'];

    function RouterConfig($urlRouterProvider) {
        $urlRouterProvider.otherwise("/");
    }

    AuthRestangular.$inject = ['Restangular', '$localStorage'];

    function AuthRestangular(Restangular, $localStorage) {
      return Restangular.withConfig(configure);

      function configure (RestangularConfigurer) {
        RestangularConfigurer.setBaseUrl('/api');
        RestangularConfigurer.setDefaultHeaders({
          Authorization: 'Bearer '+$localStorage.authToken
        });
        }
    }

})();