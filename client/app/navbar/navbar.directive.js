(function() {
    'use strict';

    angular
        .module('app.navbar')
        .directive('navbar', navbar)
        .controller('NavbarController', NavbarController);

    NavbarController.$inject = ['Auth','User'];

    function NavbarController (Auth, User) {
        var vm = this;
        vm.username = 'demo';
        vm.password = 'demo';
        vm.login = function() {
            Auth.login(vm.username,vm.password);
            vm.user = User.get();
        }
        vm.isLoggedIn = Auth.isLoggedIn;
        vm.user = User.get()
        vm.logout = Auth.logout;
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