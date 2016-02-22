(function() {
  'use strict';

  angular
    .module('app.profile')
    .controller('Profile', Profile);

  Profile.$inject = ['User'];

  function Profile(User) {

    /*jshint validthis: true */
    var vm = this;

    vm.events = [];
    vm.user = User.get();
  }
})();