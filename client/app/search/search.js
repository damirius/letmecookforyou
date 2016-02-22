(function() {
  'use strict';

  angular
    .module('app.search')
    .controller('Search', Search);

  Search.$inject = ['$rootScope','AuthRestangular'];

  function Search($rootScope, AuthRestangular) {

    /*jshint validthis: true */
    var vm = this;

    vm.events = [];
    AuthRestangular.all('events').getList().then(function (events) {

      vm.events = events;
    })
  }
})();