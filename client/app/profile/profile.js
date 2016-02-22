(function() {
  'use strict';

  angular
    .module('app.profile')
    .controller('Profile', Profile);

  Profile.$inject = ['$rootScope','AuthRestangular'];

  function Profile($rootScope, AuthRestangular) {

    /*jshint validthis: true */
    var vm = this;

    vm.events = [];
    AuthRestangular.one('me').get().then(function (user) {

      vm.user = user;
    })
  }
})();