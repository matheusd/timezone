var tzApp = angular.module('tzApp', [
  'ngRoute',
  'tzControllers',  
  'tzServices',
  'tzClasses',
  'tzDirectives'
]);

tzApp.config(['$routeProvider',
  function($routeProvider) {
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
        templateUrl: b+'timezones.html',
        controller: 'TimezonesCtrl'
      }).
      when('/timezone/:tzId', {
        templateUrl: 'timezone/:tzId.html',
        controller: 'TimezoneDetailCtrl'
      }).
      when('/users', {
        templateUrl: b+'users.html',
        controller: 'UsersCtrl'
      }).
      when('/user/login', {
        templateUrl: b+'login.html',
        controller: 'LoginCtrl'
      }).
      when('/user/logout', {
        templateUrl: b+'logout.html',
        controller: 'LogoutCtrl'
      }).
      otherwise({
        //redirectTo: '/timezones'
      });
  }]);

tzApp.config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
}]);