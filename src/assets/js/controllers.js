
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


tzControllers.controller("UsersCtrl", ['$scope', 'UserSvc',
    function ($scope, UserSvc) {        
        $scope.users = UserSvc.query();
    }
]);

tzControllers.controller("LoginCtrl", ['$scope', 'UserSvc', '$location',
    function ($scope, UserSvc, $location) {        
        $scope.user = {email: 'xxx', password: '', loginError: null};
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

tzControllers.directive('rebindSelectize', ['$timeout', 'TimezoneSvc', function ($timeout, TimezoneSvc) {
    return {
        link: function ($scope, element, attrs) {
            $(element[0]).selectize({
                create: false,
                valueField: 'name',
                labelField: 'name',
                searchField: 'name',
                dropdownParent: 'body',
                maxItems: 1,
                render: {
                    option: function(item, escape) {
                        var offset = moment().tz(item.name).format('Z')
                        return '<div style="width: 50em">' +
                            '<span class="title">' + item.name + '</span>' +
                            '<span class="description">' + item.abbr +
                            ' / GMT ' + offset + '</span>' +
                        '</div>';
                    }
                },
                load: function(query, callback) {
                    if (!query.length) return callback();
                    var regexp = RegExp(query, 'i');
                    var values = moment.tz.names().filter(function (item) {
                        //return  (!/Ral/i.test(referrer))  item.indexOf(query) > -1;
                        return regexp.test(item);
                    });
                    if (!values) return callback();
                    values = values.slice(0, 7)
                    var res = [];
                    var timestamp = moment().valueOf();
                    for (var i = 0; i < values.length; i++) {
                        var tz = moment.tz.zone(values[i]);
                        res.push({name: values[i], abbr: tz.abbr(timestamp),
                            offset: tz.offset(timestamp)});
                    }
                    return callback(res);
                },
                onChange: function (values) {
                    if (!values) return;
                    if ($scope.$parent.tz) {
                        $scope.$parent.tz.name = values;
                        $scope.$parent.tz.$save(function (tz) {
                            tz.resetGmtOffset();});
                    } else {
                        TimezoneSvc.save({name: values}, function (tz) {
                            tz.resetGmtOffset();
                            $scope.timezones.push(tz);
                        });
                        
                    }
                }
            }).focus();
        }
    };
}]);

tzControllers.controller('MenuCtrl', ['$scope', '$http', '$sce',
    function ($scope, $http, $sce) {
        $http.get("/user/menus", {headers: {Accept: "text/html"}})
            .then(function (res) {
                $scope.menusHtml = $sce.trustAsHtml(res.data);
            });
    }
]);
