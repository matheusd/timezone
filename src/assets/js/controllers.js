
var tzControllers = angular.module('tzControllers', []);

tzControllers.controller('TimezonesCtrl', ['$scope', 'TimezoneSvc', '$interval',
    function ($scope, TimezoneSvc, $interval) {
        $scope.timezones = TimezoneSvc.list();

        var fnUpdateTzs = function () {
            angular.forEach($scope.timezones, function (tz) {                
                tz.updateTimezone();
            });
        };

        $scope.timer = $interval(fnUpdateTzs, 1000);

        $scope.$on('$destroy', function() {            
            $interval.cancel($scope.timer);
        });
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