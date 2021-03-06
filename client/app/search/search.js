(function() {
  'use strict';

  angular
    .module('app.search')
    .controller('Search', Search);

  Search.$inject = ['$rootScope','AuthRestangular', 'User'];

  function Search($rootScope, AuthRestangular, User) {

    /*jshint validthis: true */
    var vm = this;

    vm.events = [];
    vm.location = 'New York City, USA';

    vm.submitSearch = function() {
        var params = {
          location: vm.location,
          radius: vm.radius,
          time_interval: vm.time_interval || 999,
          whose_place: vm.whose_place,
          who_pays: vm.who_pays,
          tags: vm.tags || ''
        };
        AuthRestangular.all('events').get('search',params).then(function(events){
          vm.events = events;
        });
    };
    /*
    AuthRestangular.all('events').getList().then(function (events) {

      vm.events = events;
     });*/
  }
})();