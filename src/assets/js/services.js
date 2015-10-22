var tzServices = angular.module('tzServices', ['ngResource']);


tzServices
    .factory('TimezoneSvc', ['$resource', '$http', 'Timezone',
        function($resource, $http, Timezone) {
            var tz = $resource('timezones/:tzId.json', {tzId: "@id"}, {
                list: {method: 'GET', isArray: true, transformResponse: [
                        $http.defaults.transformResponse[0], Timezone.transformTimezoneListingResponse
                    ]},
            });

            angular.extend(tz.prototype, Timezone.prototype);

            return tz;
        }])