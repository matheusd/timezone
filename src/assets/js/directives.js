var tzDirectives = angular.module('tzDirectives', []);

/* 
 * An Angular service which helps with creating recursive directives.
 * @author Mark Lagendijk
 * @license MIT
 */
tzDirectives.factory('RecursionHelper', ['$compile', function ($compile) {
        return {
            /**
             * Manually compiles the element, fixing the recursion loop.
             * @param element
             * @param [link] A post-link function, or an object with function(s) registered via pre and post properties.
             * @returns An object containing the linking functions.
             */
            compile: function (element, link) {
                // Normalize the link parameter
                if (angular.isFunction(link)) {
                    link = {post: link};
                }

                // Break the recursion loop by removing the contents
                var contents = element.contents().remove();
                var compiledContents;
                return {
                    pre: (link && link.pre) ? link.pre : null,
                    /**
                     * Compiles and re-adds the contents
                     */
                    post: function (scope, element) {
                        // Compile the contents
                        if (!compiledContents) {
                            compiledContents = $compile(contents);
                        }
                        // Re-add the compiled contents to the element
                        compiledContents(scope, function (clone) {
                            element.append(clone);
                        });

                        // Call the post-linking function, if any
                        if (link && link.post) {
                            link.post.apply(null, arguments);
                        }
                    }
                };
            }
        };
    }]);

tzDirectives.directive('rebindSelectize', ['$timeout', 'TimezoneSvc', function ($timeout, TimezoneSvc) {
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

tzDirectives.directive('tzMenu', 
    ['RecursionHelper', '$compile', '$templateCache', 
    function (RecursionHelper, $compile, $templateCache) {        
        return {
            //restrict: '',
            scope: {"menu": "=", "watchProxy": "="},            
            templateUrl: "/assets/templates/menuItem.html",
            compile: function (element) {
                return RecursionHelper.compile(element, function($scope, iElement, iAttrs, controller, transcludeFn) {                
                    //$compile(element.contents())($scope);
                    if ($scope.watchProxy) {
                        console.log('watching ' + $scope.watchProxy);
                        $scope.$watch('watchProxy', function () {
                            console.log('trigged proxy watch');
                            console.log($scope.menu);             
                            //element.html("");
                            $compile(element.contents())($scope);
                        });
                    }
                });
            }
        }
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