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


tzServices
    .factory('UserSvc', ['$http', 
        function ($http) {
            console.log('ha');
            function UserSvc($http) {
                var svc = {
                    url: '',
                    isLoggedIn: false,
                    login: login,
                    logout: logout,
                    checkUserLoggedIn: checkUserLoggedIn,
                    userId: -1
                }

                var httpConfig = {
                    withCredentials : true
                };

                return svc;

                function login(email, password) {
                    var data = {email: email, password: password};
                    var url = svc.url + "/user/login";
                    return $http.post(url, data, httpConfig)
                        .then(loggedIn)                        
                }

                function logout() {
                    this.isLoggedIn = false;
                    this.userId = undefined;
                    return $http
                        .post(svc.url + '/sys/logout', {}, httpConfig)                        
                }

                function checkUserLoggedIn() {
                    return $http
                        .post(svc.url + "/user/isLoggedIn", {}, httpConfig)
                        .then(loggedIn)
                        .catch(notLoggedIn)
                }


                function loggedIn(response) {
                    svc.userId = response.data.userId;
                    svc.isLoggedIn = true;
                    return response;
                }
                
                function notLoggedIn(response) {
                    svc.userId = -1;
                    svc.isLoggedIn = false;
                    return response;
                }
            }            
            
            /*
            function UserSvc() {
                var usr = $resource('users/:userId.json', {userId: "@id"}, {
                    query: {method: 'GET', isArray: true, transformResponse: [
                            $http.defaults.transformResponse[0], UserSvc.transformUserListingResponse
                    ]},                    
                });
                return usr;
            }

            UserSvc.transformUserListingResponse = function (data) {
                return data.users;
            };

            return new UserSvc();
            */
           return new UserSvc($http);
        }]);