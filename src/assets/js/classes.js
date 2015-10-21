var tzClasses = angular.module('tzClasses', []);

tzClasses
    .factory('Timezone', ['$interval', function ($interval) {
        function Timezone(tzData) {
            var tzNames = moment.tz.names();

            if (tzNames.indexOf(tzData.name) < 0) {
                this.name = tzData.name;
                this.gmtOffset = "00:00";
                this.abbr = "GMT";
                this.timeInTz = moment().format("llll");
                this.hasTimezone = false;
                return;
            }

            var timestamp = moment().valueOf();
            var mmtz = moment().tz(tzData.name);
            var zone = moment.tz.zone(tzData.name);

            this.name = tzData.name || "";
            this.gmtOffset = mmtz.format('Z');
            this.abbr = zone.abbr(timestamp);
            this.timeInTz = mmtz.format("llll");
            this.hasTimezone = true;            
        }

        Timezone.prototype.updateTimezone = function () {
            if (!this.hasTimezone) {
                this.timeInTz = moment().format("llll");
            } else {                
                this.timeInTz = moment().tz(this.name).format("llll");                
            }
        }

        Timezone.transformTimezoneListingResponse = function(data, headers) {
            var res = data.timezones.map(function (curValue) {
                return new Timezone(curValue);
            });
            return res;
        }

        return Timezone;

    }]);

