function redirectContentDiv(url) {
    $.ajax({
        url: url,
        method: 'GET',
        success: function (data) {                
            $("#content-div").empty().html(data);        
        },
        error: function (xhr) {                                
            $("#content-div").empty().html(xhr.responseText);        
        }
    });                
}

function reloadMenus() {
    $("#navbar").empty().load('/user/menus');
}

var currentUser = null;

function reloadCurrentUserData() {
    jsonAjax({
        url: '/user/profile',
        success: function (data) {
            currentUser = data.user;
        },
        error: function () {
            currentUser = null;
        }
    })
}

function jsonAjax(options) {
    var defaultOpts = {
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {
            alert("Success!");
        },
        error: function (xhr) {
            try {
                var json = JSON.parse(xhr.responseText);
            } catch (e) {
                alert("Error!\n" + xhr.responseText);
                return;
            }
            if (json && json.status == 'error') {
                if (json.class == 'ToptalTimezone\\Orm\\ValidationException') {
                    var msg = json.violations.join("\n");
                    alert(msg);
                } else {
                    alert(json.errorMsg);
                }
            } else {
                alert("Error!\n" + xhr.responseText);
            }
        }    
    };
    var opts = $.extend(null, defaultOpts, options);
    if (options.jsonData) {
        opts.data = JSON.stringify(options.jsonData);
    };
    $.ajax(opts);
}

function submitSpaForm(e) {
    e.preventDefault();
    e.stopPropagation();
    var $form = $(e.target);
    jsonAjax({
        url: $form.attr('action'),
        method: $form.attr("method"),
        jsonData: $form.serializeJSON(),
        success: function (data) {
            if ($form.attr('spaAfterSubmit')) {
                window[$form.attr('spaAfterSubmit')].apply(data);
            }
        },
    });
    return false;
}

function linkClicked(e) {
    var href = $(e.target).attr("href");
    if (href == "#") return;
    $(".dropdown.open").removeClass("open");
    redirectContentDiv(href);
    //e.preventDefault();
    //e.stopPropagation();
    return false;
}

function loggedIn() {
    redirectContentDiv("/");
    reloadMenus();
    reloadCurrentUserData();
}

function registrationComplete() {
    redirectContentDiv("/user/login");
}

function rebindSelectizeTimezone($input, url) {
    $(".selectize-dropdown").remove();
    $input.selectize({
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
            jsonAjax({
                url: url,
                method: 'POST',
                jsonData: {name: values},
                success: function () {
                    alert('Added Timezone!');
                    redirectContentDiv($("#newTzFormUri").val());
                }
            });
        }
    });
}

var loadedTimezones = [];

function reloadTimezones(timezones) {
    var $timezones = $("#timezones").empty();
    var $model = $("#modelTimezone");
    var tzNames = moment.tz.names();
    var timestamp = moment().valueOf();
    for (var i = 0; i < timezones.length; i++) {
        var tz = timezones[i];
        if (tzNames.indexOf(tz.name)< 0) continue;
        var mmtz = moment().tz(tz.name);
        var zone = moment.tz.zone(tz.name);
        var abbr = zone.abbr(timestamp);
        var offset = mmtz.format('Z');

        var $tz = $model.clone().removeAttr("id");
        $tz.attr("id", "tz" + tz.id);
        $tz.data('timezone', tz);
        $tz.find(".tzName").html(tz.name);
        $tz.find(".tzDescr").html(abbr + ' / GMT ' + offset);
        $tz.find(".tzTime").html(mmtz.format('llll'));

        $tz.appendTo($timezones);
    }

    loadedTimezones = timezones;
    rebindSelectizeTimezone($('.newTzName'), $("#newTzFormUri").val());
}

function updateTimezones() {
    if (loadedTimezones.length == 0) return;
    for (var i = 0; i < loadedTimezones.length; i++) {
        var tz = loadedTimezones[i];
        var $tz = $("#tz" + tz.id);
        if ($tz.length == 0) continue;

        var mmtz = moment().tz(tz.name);
        $tz.find(".tzTime").html(mmtz.format('llll'));
    }
}

function btnEditTimezoneClicked(e) {
    var $row = $(e.target).closest(".timezone");
    $row.find(".tzData").hide();
    $row.find(".tzEditData").show();
    var tz = $row.data('timezone');
    rebindSelectizeTimezone($row.find(".tzEditData input"), "/timezone/" + tz.id);
    $row.find(".tzEditData input").focus();
}

function btnDelTimezoneClicked(e) {    
    var tz = $(e.target).closest(".timezone").data("timezone");
    jsonAjax({
        url: '/timezone/' + tz.id,
        method: 'delete',
        success: function () {
            alert("Deleted timezone");
            redirectContentDiv($("#newTzFormUri").val());
        }
    })
}

var ROLE_USER = 0;
var ROLE_MANAGER = 1;
var ROLE_ADMIN = 999;

var ROLE_NAMES = {
    0: 'User',
    1: 'Manager',
    999: 'Admin'
}

function reloadUsers(users) {
    var $users = $("#userListing tbody").empty();    
    for (var i = 0; i < users.length; i++) {
        var u = users[i];        
        var $row = $("#modelUserRow").clone().removeAttr("id");
        $row.data("user", u);
        $row.find(".name").html(u.name);
        $row.find(".id").html(u.id);
        $row.find(".role").html(ROLE_NAMES[u.role]);

        //uncomment to hide link timezone listing for non-admin users
        /*if (currentUser.role != ROLE_ADMIN) {
            $row.find(".btnListUserTz").remove();
        }*/
        $row.appendTo($users);
    }
}

function btnDelUserClicked(e) {
    var user = $(e.target).closest("tr").data('user');
    jsonAjax({
        url: '/user/' + user.id,
        method: "delete",
        success: function() {
            alert('User deleted!');
            redirectContentDiv("/users");
        }
    });
}

function btnEditUserClicked(e) {
    var user = $(e.target).closest("tr").data('user');
    redirectContentDiv("/user/" + user.id);
}

function btnListUserTzClicked(e) {
    var user = $(e.target).closest("tr").data('user');
    redirectContentDiv("/timezones/fromUser/" + user.id);
}

function editUser(user) {
    var $form = $("#editUserForm");
    $form.find("#name").val(user.name);
    $form.find("#email").val(user.email);
    $form.find("#role").val(user.role);
}

function newUserCreated() {
    alert("User Created!");
    redirectContentDiv("/users");
}

function saveOk() {
    alert("Saved!")
}

function hookJqueryValUnescape() {
    $.valHooks.text = {
        set: function( elem, value ) {
          value = _.unescape(value);
          elem.value = value;
          return value;
        }
    };
}


function setupIndex() {
    hookJqueryValUnescape();
    $("body").on("submit", ".spa_form", submitSpaForm);
    $("body").on("click", "a", linkClicked);    
    $("body").on("click", ".btnDelTimezone", btnDelTimezoneClicked);
    $("body").on("click", ".btnDelUser", btnDelUserClicked);
    $("body").on("click", ".btnEditUser", btnEditUserClicked);
    $("body").on("click", ".btnListUserTz", btnListUserTzClicked);
    $("body").on("click", ".tzData", btnEditTimezoneClicked);
    reloadMenus();
    reloadCurrentUserData();
    if (initialRoute) {
        redirectContentDiv(initialRoute);
    }
    setInterval(updateTimezones, 1);
}

$(document).ready(setupIndex);