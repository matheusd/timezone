var tzDirectives = angular.module('tzDirectives', []);

tzDirectives.directive('rebindSelectize', ['$timeout', 
    function ($timeout) {
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
                        $scope.TimezoneSvc.save({name: values}, function (tz) {
                            tz.resetGmtOffset();
                            $scope.timezones.push(tz);
                        });
                        
                    }
                }
            }).focus();
        }
    };
}]);

tzDirectives.directive('tzMenu2', 
    [
        function () {
            return {
                scope: {"menu": "=", "watchProxy": "="},            
                templateUrl: "/assets/templates/menuItem.html",                
            }            
        }
    ]
);