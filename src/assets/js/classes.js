var tzClasses = angular.module('tzClasses', []);

tzClasses
    .factory('Timezone', ['$interval', function ($interval) {
        function Timezone(tzData) {
            this.reloadTzData(tzData);
        }

        Timezone.prototype.reloadTzData = function (tzData) {
            var tzNames = moment.tz.names();
            this.id = tzData.id;

            if (tzNames.indexOf(tzData.name) < 0) {
                this.name = tzData.name;
                this.gmtOffset = "00:00";
                this.abbr = "GMT";
                this.timeInTz = moment().format("llll");
                this.hasTimezone = false;
                this.inEditMode = false;
                return;
            }

            this.name = tzData.name || "";
            this.hasTimezone = true;
            this.inEditMode = false;

            this.resetGmtOffset();
        };

        Timezone.prototype.resetGmtOffset = function () {            
            var timestamp = moment().valueOf();
            var mmtz = moment().tz(this.name);
            var zone = moment.tz.zone(this.name);

            this.gmtOffset = mmtz.format('Z');
            this.abbr = zone.abbr(timestamp);
            this.timeInTz = mmtz.format("llll");
            this.hasTimezone = true;
        };

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

tzClasses
    .factory('User', [function () {
        function User(userData) {
            this.reloadUserData(userData);
        }

        User.prototype.reloadUserData = function (userData) {
            this.id = userData.id;
            this.name = userData.name;
            this.role = userData.role;
        };
        
        User.transformListingResponse = function(data, headers) {
            var res = data.users.map(function (curValue) {
                return new User(curValue);
            });
            return res;
        };

        return User;

    }]);

