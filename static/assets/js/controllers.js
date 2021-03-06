(function (angular) {
    'use strict';


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


    tzControllers.controller('TimezonesCtrl', ['$scope', '$route', '$interval',
        function ($scope, $route, $interval) {
            //ListSvc parametrized on the $routeProvider (to be able to
            //switch between TimezoneSvc and UserTimezonesSvc)
            var timezones = $route.current.locals.timezones;
            $scope.TimezoneSvc = $route.current.locals.TimezoneSvc;
            function TimezonesCtrl() {
                $scope.timezones = timezones;

                var fnUpdateTzs = function () {
                    angular.forEach($scope.timezones, function (tz) {
                        tz.updateTimezone();
                    });
                };

                $scope.timer = $interval(fnUpdateTzs, 1000);

                $scope.$on('$destroy', function () {
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
        function ($scope, $routeParams, Timezone) {
            $scope.phone = Phone.get({phoneId: $routeParams.phoneId}, function (phone) {
                $scope.mainImageUrl = phone.images[0];
            });

            $scope.setImage = function (imageUrl) {
                $scope.mainImageUrl = imageUrl;
            };
        }]);


    tzControllers.controller("UsersCtrl", ['$scope', 'UserListSvc', '$location',
        function ($scope, UserListSvc, $location) {
            $scope.users = UserListSvc.list();
            $scope.editingUser = {name: "", email: "", role: 0, id: null};
            $scope.editUser = function (user) {
                $location.path("/users/" + user.id);
            };
            $scope.deleteUser = function (user) {
                $scope.editError = null;
                user.$delete(successDeleting, errorDeleting);
            };
            $scope.userTimezones = function (user) {
                $location.path("/timezones/fromUser/" + user.id);
            };

            function successDeleting() {
                $scope.users = UserListSvc.list();
            }
            function errorDeleting(response) {
                alert(response.data.errorMsg);
            }
        }
    ]);

    tzControllers.controller("EditUserCtrl", ['$scope', 'UserListSvc',
        '$location', '$routeParams', 'UserSvc',
        function ($scope, UserListSvc, $location, $routeParams, UserSvc) {
            var user = UserListSvc.get({id: $routeParams.id}, function () {
                user.password = "";
                user.password2 = "";
            });
            $scope.allowedRoles = UserSvc.allowedRoles;
            $scope.user = user;
            $scope.editError = null;
            $scope.editUser = function () {
                $scope.editError = null;
                $scope.user.$save(successEditingUser, errorEditingUser);
            };
            function successEditingUser() {
                $scope.editError = false;
            }
            function errorEditingUser(response) {
                $scope.editError = response.data.errorMsg;
            }
        }
    ]);

    tzControllers.controller("LoginCtrl", ['$scope', 'UserSvc', '$location',
        function ($scope, UserSvc, $location) {
            $scope.user = {email: '', password: '', loginError: null};
            $scope.tryLogin = function () {
                $scope.user.loginError = null;
                UserSvc
                        .login($scope.user.email, $scope.user.password)
                        .then(function () {
                            $location.path("/timezones");
                        })
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
                        .then(function () {
                            $location.path("/user/login");
                        })
                        .catch(function () {
                            $location.path("/user/login");
                        });
            };
        }
    ]);

    tzControllers.controller("RegisterCtrl", ['$scope', 'UserSvc', '$location',
        function ($scope, UserSvc, $location) {
            $scope.newUser = {email: '', password: '', name: '',
                password2: '', registerError: null};
            $scope.registerNewUser = function () {
                $scope.newUser.registerError = null;
                UserSvc
                        .register($scope.newUser.name, $scope.newUser.email,
                                $scope.newUser.password, $scope.newUser.password2)
                        .then(function () {
                            $location.path("/timezones");
                        })
                        .catch(function (response) {
                            $scope.newUser.registerError = response.data.errorMsg;
                        });
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
})(angular);