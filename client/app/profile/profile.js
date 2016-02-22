(function() {
  'use strict';

  angular
    .module('app.profile')
    .controller('Profile', Profile);

  Profile.$inject = ['$rootScope','User'];

  function Profile($rootScope, User) {

    /*jshint validthis: true */
    var vm = this;

    vm.events = [];
    vm.user = User.get();
  }
})();