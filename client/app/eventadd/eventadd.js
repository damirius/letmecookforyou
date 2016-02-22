(function() {
  'use strict';

  angular
    .module('app.eventadd')
    .controller('EventAdd', EventAdd);

  EventAdd.$inject = ['$rootScope','AuthRestangular', '$state'];

  function EventAdd($rootScope, AuthRestangular, $state) {

    /*jshint validthis: true */
    var vm = this;

    vm.events = [];
    vm.who_pays = 1;
    vm.whose_place = 1;
    vm.country = "";
    vm.countries = [];
    vm.submit = submit;

    function submit()
    {
      var params = {
        city: vm.city,
        country: vm.country,
        whose_place: vm.whose_place,
        who_pays: vm.who_pays,
        cost_estimate: vm.cost_estimate,
        tags: vm.tags,
        when: vm.when,
        description: vm.description,
        meal_name: vm.meal_name
      };
      AuthRestangular.all('events').post(params).then(function(event){
          $state.go('event', {eventId: event.id})
        });
    }
    AuthRestangular.all('countries').getList().then(function(countries){
      vm.countries = countries;
    });
    AuthRestangular.all('events').getList().then(function (events) {
      vm.events = events;
    });

  }
})();