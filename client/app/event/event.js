(function() {
  'use strict';

  angular
    .module('app.event')
    .controller('Event', Event);

  Event.$inject = ['$rootScope','$stateParams','AuthRestangular','User'];

  function Event($rootScope, $stateParams, AuthRestangular,User) {

    /*jshint validthis: true */
    var vm = this;
    vm.user = User.get();
    vm.who_pays = new Array('', 'I pay', 'You pay', 'We split');
    vm.whose_place = new Array('','My place', 'Your Place ', 'Other place');
    vm.events = [];
    AuthRestangular.one('events', $stateParams.eventId).get().then(function (event) {

      vm.event = event;
      vm.isHost = function()
      {
        if(vm.user.id==event.host.id)
          return true;
        else
          return false;
      }
    });

  }
})();