
var tzControllers = angular.module('tzControllers', []);

tzControllers.controller("HomeCtrl", ['$scope', 'UserSvc', '$location',
    function ($scope, UserSvc, $location) {
        UserSvc
            .checkUserLoggedIn()
            .then(redirAfterLoginCheck);
    
        function redirAfterLoginCheck() {            
            if (UserSvc.isLoggedIn) {
                $location.path("/timezones");
            } else {
                $location.path("/user/login");
            }
        }
    }
]);


tzControllers.controller('TimezonesCtrl', ['$scope', 'TimezoneSvc', '$interval',
    function ($scope, TimezoneSvc, $interval) {
        function TimezonesCtrl() {            
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

            this.listen();
        }
        
        angular.extend(TimezonesCtrl.prototype, {
            changeEditMode: function (tz) {
                tz.inEditMode = true;
            },
            deleteTimezone: function (tz) {
                tz.$delete(function (tz) {
                    for (var i = 0; i < $scope.timezones.length; i++) {
                        if ($scope.timezones[i].id == tz.id) {
                            $scope.timezones.splice(i, 1);
                            break;
                        }
                    }
                });
            },

            listen: function () {
                $scope.changeEditMode = angular.bind(this, this.changeEditMode);
                $scope.deleteTimezone = angular.bind(this, this.deleteTimezone);
            }
        });

        return new TimezonesCtrl();
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


tzControllers.controller("UsersCtrl", ['$scope', 'UserListSvc',
    function ($scope, UserListSvc) {        
        $scope.users = UserListSvc.list();
    }
]);

tzControllers.controller("LoginCtrl", ['$scope', 'UserSvc', '$location',
    function ($scope, UserSvc, $location) {        
        $scope.user = {email: '', password: '', loginError: null};
        $scope.tryLogin = function () {
            $scope.user.loginError = null;
            UserSvc
                    .login($scope.user.email, $scope.user.password)
                    .then(function () {$location.path("/timezones");})
                    .catch(function (response) {                        
                        $scope.user.loginError = response.data.errorMsg;
                    });
        };
    }
]);

tzControllers.controller("LogoutCtrl", ['$scope', 'UserSvc', '$location',
    function ($scope, UserSvc, $location) {        
        $scope.tryLogout = function () {            
            UserSvc
                    .logout()
                    .then(function () {$location.path("/user/login");})
                    .catch(function () {$location.path("/user/login");});
        };
    }
]);

tzControllers.controller("ProfileCtrl", ['$scope', 'UserSvc',
    function ($scope, UserSvc) {     
        $scope.profile = {name: "", email: "", password: "", password2: ""};
        $scope.updateError = null;
        $scope.updateProfile = function () {
            $scope.updateError = null;
            UserSvc.setUserProfile($scope.profile)
                    .then(function () {
                        $scope.updateError = false;
                    })
                    .catch(function (response) {
                        console.log(response.data);
                        $scope.updateError = response.data.errorMsg;
                    });
        };
        UserSvc.getUserProfile().then(function (response) {            
            angular.extend($scope.profile, response.data.user);            
        });
    }
]);

tzControllers.controller('MenuCtrl', ['$scope', 'UserSvc',
    function ($scope, UserSvc) {
        $scope.menus = UserSvc.menus();
    }
]);
