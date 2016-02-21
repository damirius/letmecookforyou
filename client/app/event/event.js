(function() {
  'use strict';

  angular
    .module('app.event')
    .controller('Event', Event);

  Event.$inject = ['$rootScope','$stateParams','AuthRestangular'];

  function Event($rootScope, $stateParams, AuthRestangular) {

    /*jshint validthis: true */
    var vm = this;

    vm.events = [];
    AuthRestangular.one('events', $stateParams.eventId).get().then(function (event) {

      vm.event = event;
    })
  }
})();