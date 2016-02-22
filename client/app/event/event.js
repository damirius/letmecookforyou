(function () {
    'use strict';

    angular
        .module('app.event')
        .controller('Event', Event);

    Event.$inject = ['$rootScope', '$stateParams', 'AuthRestangular', 'User', 'Upload', '$scope', '$localStorage'];

    function Event($rootScope, $stateParams, AuthRestangular, User, Upload, $scope, $localStorage) {

        /*jshint validthis: true */
        var vm = this;
        vm.user = User.get();
        vm.who_pays = new Array('', 'I pay', 'You pay', 'We split');
        vm.whose_place = new Array('', 'My place', 'Your Place ', 'Other place');
        vm.events = [];
        vm.upload = upload;
        vm.submit = submit;

        AuthRestangular.one('events', $stateParams.eventId).get().then(function (event) {

            vm.event = event;
            vm.isHost = function () {
                if (vm.user.id == event.host.id)
                    return false;
                else
                    return true;
            }
        });


        function submit() {
            if ($scope.form.file.$valid && vm.file) {
                upload(vm.file);
            }
        };


        function upload(file) {
            console.log(file);
            Upload.upload({
                url: '/api/events/' + $stateParams.eventId + '/gallery',
                data: {file: file},
                method: 'POST',
                headers: {
                    'Content-Type': file.type,
                    'Authorization': 'Bearer ' + $localStorage.authToken
                }
            }).then(function (resp) {
                vm.user = User.get(true);
                AuthRestangular.one('events', $stateParams.eventId).get().then(function (event) {
                    vm.event = event;
                });
            }, function (resp) {
                console.log('Error status: ' + resp.status);
            }, function (evt) {
                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
            });
        }

    }
})();