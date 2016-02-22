(function () {
    'use strict';

    angular
        .module('app.profile')
        .controller('Profile', Profile);

    Profile.$inject = ['User', 'Upload', '$scope', '$localStorage', 'AuthRestangular'];

    function Profile(User, Upload, $scope, $localStorage, AuthRestangular) {

        /*jshint validthis: true */
        var vm = this;

        vm.gallery = [];
        vm.events = [];
        vm.user = User.get();
        vm.upload = upload;
        vm.submit = submit;

        AuthRestangular.all('events').get('hosting').then(function (events) {
            vm.events = events;
            angular.forEach(events, function (event) {
                vm.gallery = vm.gallery.concat(event.gallery);
            })
        })


        function submit() {
            if ($scope.form.file.$valid && vm.file) {
                upload(vm.file);
            }
        };


        function upload(file) {
            console.log(file);
            Upload.upload({
                url: '/api/me/profile-picture',
                data: {file: file},
                method: 'POST',
                headers: {
                    'Content-Type': file.type,
                    'Authorization': 'Bearer ' + $localStorage.authToken
                }
            }).then(function (resp) {
                vm.user = User.get(true);
            }, function (resp) {
                console.log('Error status: ' + resp.status);
            }, function (evt) {
                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
            });
        }
    }
})();