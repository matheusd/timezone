
var tzControllers = angular.module('tzControllers', []);

tzControllers.controller('TimezonesCtrl', ['$scope', 'Timezone',
    function($scope, Timezone) {
        $scope.timezones = Timezone.list();
        $scope.orderProp = 'age';
    }]);

tzControllers.controller('TimezoneDetailCtrl', ['$scope', '$routeParams', /*'Timezone',*/
    function($scope, $routeParams, Timezone) {
        $scope.phone = Phone.get({phoneId: $routeParams.phoneId}, function(phone) {
            $scope.mainImageUrl = phone.images[0];
        });

        $scope.setImage = function(imageUrl) {
            $scope.mainImageUrl = imageUrl;
        };
    }]);


tzControllers.controller("UserCtrl", ['$scope',
    function ($scope) {
        
    }
])