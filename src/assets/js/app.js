var tzApp = angular.module('tzApp', [
  'ngRoute',
  'tzControllers',
  //'tzFilters',
  'tzServices',
  'tzClasses',
]);

tzApp.config(['$routeProvider',
  function($routeProvider) {
    var b = '/assets/templates/';
    $routeProvider.
      when('/', {
        redirectTo: '/timezones'
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
        templateUrl: b+'user/login.html',
        controller: 'UserCtrl'
      }).
      otherwise({
        //redirectTo: '/timezones'
      });
  }]);

tzApp.config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
}]);