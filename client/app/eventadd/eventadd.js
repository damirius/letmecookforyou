(function() {
  'use strict';

  angular
    .module('app.eventadd')
    .controller('EventAdd', EventAdd);

  EventAdd.$inject = ['$rootScope','AuthRestangular'];

  function EventAdd($rootScope, AuthRestangular) {

    /*jshint validthis: true */
    var vm = this;

    vm.events = [];
    vm.who_pays = 1;
    vm.whose_place = 1;
    vm.country = "";
    AuthRestangular.all('events').getList().then(function (events) {
      vm.events = events;
    });

  }
})();