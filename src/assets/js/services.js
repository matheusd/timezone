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
        }]);
    
tzServices
    .factory('UserTimezonesSvc', ['$resource', '$http', 'Timezone',
        function($resource, $http, Timezone) {
            return {
                timezonesFromUser: function (userId) {
                    var tz = $resource('/timezones/fromUser/:userId/:tzId.json', {tzId: "@id", userId: userId}, {
                        list: {url: '/timezones/fromUser/:userId', params: {userId: userId},
                                method: 'GET', isArray: true, transformResponse: [
                                $http.defaults.transformResponse[0], Timezone.transformTimezoneListingResponse
                            ]},
                    });

                    angular.extend(tz.prototype, Timezone.prototype);

                    return tz;
                }
            };            
        }]);
    
tzServices
    .factory('UserListSvc', ['$resource', '$http', 'User',
        function($resource, $http, User) {
            var us = $resource('users/:id.json', {id: "@id"}, {
                list: {method: 'GET', isArray: true, transformResponse: [
                        $http.defaults.transformResponse[0], User.transformListingResponse
                    ]}
            });

            angular.extend(us.prototype, User.prototype);

            return us;
        }]);

tzServices
    .factory('UserSvc', ['$http', 
        function ($http) {            
            function UserSvc($http) {
                var svc = {
                    url: '',
                    isLoggedIn: false,
                    login: login,
                    logout: logout,
                    checkUserLoggedIn: checkUserLoggedIn,
                    menus: menus,
                    updateMenus: updateMenus,
                    getUserProfile: getUserProfile,
                    setUserProfile: setUserProfile,                    
                    userId: -1,
                    allowedRoles: [
                        {role: 0, descr: "User"},
                        {role: 1, descr: "Manager"},
                        {role: 999, descr: "Admin"},
                    ]
                }

                var httpConfig = {
                    withCredentials : true
                };
                
                var menusObj = {__touched: Math.random(),
                    items: []};

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
                        .post(svc.url + '/user/logout', {}, httpConfig)
                        .then(updateMenus());
                }

                function checkUserLoggedIn() {
                    return $http
                        .get(svc.url + "/user/isLoggedIn", {}, httpConfig)
                        .then(loggedIn)
                        .catch(notLoggedIn)
                }


                function loggedIn(response) {
                    svc.userId = response.data.userId;
                    svc.isLoggedIn = true;
                    svc.updateMenus();
                    return response;
                }
                
                function notLoggedIn(response) {
                    svc.userId = -1;
                    svc.isLoggedIn = false;
                    svc.updateMenus();
                    return response;
                }
                
                function updateMenus() {
                    return $http
                        .get(svc.url + "/user/menus", {}, httpConfig)
                        .then(menusUpdated)                        
                }
                
                function menusUpdated(response) {                    
                    angular.extend(menusObj.items, response.data);
                    menusObj.__touched = Math.random();                    
                }
                
                function menus() {
                    return menusObj;
                }
                
                function getUserProfile() {
                    return $http
                        .get(svc.url + "/user/profile", {}, httpConfig)
                }
                
                function setUserProfile(profile) {
                    return $http
                        .post(svc.url + "/user/profile", profile, httpConfig)
                }
            }            
            
           return new UserSvc($http);
        }]);