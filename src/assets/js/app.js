var tzApp = angular.module('tzApp', [
  'ngRoute',
  'tzControllers',
  //'tzFilters',
  'tzServices',
  'tzClasses',
]);

tzApp.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/timezones', {
        templateUrl: 'timezones.html',
        controller: 'TimezonesCtrl'
      }).
      when('/timezone/:tzId', {
        templateUrl: 'timezone/:tzId.html',
        controller: 'TimezoneDetailCtrl'
      }).
      when('/user/login', {
        templateUrl: 'user/login.html',
        controller: 'UserCtrl'
      }).
      otherwise({
        redirectTo: '/timezones'
      });
  }]);

tzApp.config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
}]);