var tzServices = angular.module('tzServices', ['ngResource']);

Timezone = function (tzData) {
    var tzNames = moment.tz.names();
    if (tzNames.indexOf(tzData.name) < 0) {
        this.name = tzData.name;
        this.gmtOffset = "00:00";
        this.abbr = "GMT";
        return;
    }

    var timestamp = moment().valueOf();
    var mmtz = moment().tz(tzData.name);
    var zone = moment.tz.zone(tzData.name);

    this.name = tzData.name || "";
    this.gmtOffset = mmtz.format('Z');
    this.abbr = zone.abbr(timestamp);

};


tzServices.transformTimezoneListingResponse = function(data, headers) {    
    var res = data.timezones.map(function (curValue) {
        return new Timezone(curValue);
    });    
    return res;
};

tzServices.factory('Timezone', ['$resource', '$http',
    function($resource, $http) {
        var tz = $resource('timezones/:tzId.json', {}, {
            list: {method: 'GET', isArray: true, transformResponse: [
                    $http.defaults.transformResponse[0], tzServices.transformTimezoneListingResponse
                ]}
        });        
        
        return tz;
    }]);
