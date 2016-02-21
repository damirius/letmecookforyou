(function() {
    'use strict';

    angular
        .module('app.navbar')
        .directive('navbar', navbar)
        .controller('NavbarController', NavbarController);

    navbar.$inject=['Auth'];
    NavbarController.$inject = ['$scope','Auth'];

    function NavbarController ($scope,Auth) {
        var vm = this;
        vm.username = 'demo2';
        vm.password = 'demo2';
        vm.login = function()
        {
          Auth.login(vm.username,vm.password);
        }
        vm.isLoggedIn = Auth.isLoggedIn;
    }

    function navbar () {

        var directive = {
            link: link,
            restrict: 'E',
            templateUrl: 'app/navbar/navbar.html',
            controller: NavbarController,
            controllerAs: 'vm',
            bindToController: true
        };

        return directive;

        function link(scope, element, attrs) {

        }
    }
})();