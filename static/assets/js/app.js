(function (angular) {
    'use strict';


    var tzApp = angular.module('tzApp', [
        'ngRoute',
        'tzControllers',
        'tzServices',
        'tzClasses',
        'tzDirectives'
    ]);

    tzApp.config(['$routeProvider',
        function ($routeProvider) {
            var b = '/assets/templates/';
            $routeProvider.
                    when('/', {
                        redirectTo: '/home'
                    }).
                    when('/home', {
                        controller: 'HomeCtrl',
                        template: ""
                    }).
                    when('/timezones', {
                        templateUrl: b + 'timezones.html',
                        controller: 'TimezonesCtrl',
                        resolve: {
                            timezones: ['TimezoneSvc', function (TimezoneSvc) {
                                    return TimezoneSvc.list();
                                }],
                            TimezoneSvc: 'TimezoneSvc'
                        }
                    }).
                    when('/timezone/:tzId', {
                        templateUrl: 'timezone/:tzId.html',
                        controller: 'TimezoneDetailCtrl'
                    }).
                    when('/timezones/fromUser/:userId', {
                        templateUrl: b + 'timezones.html',
                        controller: 'TimezonesCtrl',
                        resolve: {
                            timezones: ['UserTimezonesSvc', '$route', function (UserTimezonesSvc, $route) {
                                    return UserTimezonesSvc
                                            .timezonesFromUser($route.current.params.userId)
                                            .list();
                                }],
                            TimezoneSvc: ['UserTimezonesSvc', '$route', function (UserTimezonesSvc, $route) {
                                    return UserTimezonesSvc
                                            .timezonesFromUser($route.current.params.userId)
                                }]
                        }
                    }).
                    when('/users', {
                        templateUrl: b + 'users.html',
                        controller: 'UsersCtrl'
                    }).
                    when('/users/:id', {
                        templateUrl: b + 'editUser.html',
                        controller: 'EditUserCtrl'
                    }).
                    when('/user/login', {
                        templateUrl: b + 'login.html',
                        controller: 'LoginCtrl'
                    }).
                    when('/user/new', {
                        templateUrl: b + 'register.html',
                        controller: 'RegisterCtrl'
                    }).
                    when('/user/logout', {
                        templateUrl: b + 'logout.html',
                        controller: 'LogoutCtrl'
                    }).
                    when('/user/profile', {
                        templateUrl: b + 'profile.html',
                        controller: 'ProfileCtrl'
                    }).
                    otherwise({
                        //redirectTo: '/timezones'
                    });
        }]);

    tzApp.config(['$httpProvider', function ($httpProvider) {
            $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
        }]);
})(angular);