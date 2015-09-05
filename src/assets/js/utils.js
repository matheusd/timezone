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
    redirectContentDiv(href);
    //e.preventDefault();
    //e.stopPropagation();
    return false;
}

function loggedIn() {
    redirectContentDiv("/");
}

function registrationComplete() {
    redirectContentDiv("/user/login");
}

function newTzNameSelected(e) {
    if (e.which != 13) return;
    jsonAjax({
        url: $("#newTzFormUri").val(),
        method: 'POST',
        jsonData: {name: $(e.target).val()}
    });
}

function reloadTimezones(timezones) {
    var $timezones = $("#timezones").empty();
    for (var i = 0; i < timezones.length; i++) {
        var tz = timezones[i];
        var $tz = $("<div class='row timezone'>");
        $tz.data('timezone', tz);

        var $col = $("<div class='col-sm-4 timezoneData'>").appendTo($tz);
        $("<div class='tzName col-sm-11'>").html(tz.name).appendTo($col);

        $("<div class='col-sm-1'><button type='button' class='btn btn-default btnDelTimezone'><i class='glyphicon glyphicon-remove'>").appendTo($col);

        $tz.appendTo($timezones);
    }
}

function btnDelTimezoneClicked(e) {    
    var tz = $(e.target).closest(".timezone").data("timezone");
    jsonAjax({
        url: '/timezone/' + tz.id,
        method: 'delete'
    })
}

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
        $row.appendTo($users);
    }
}

function btnDelUserClicked(e) {
    var user = $(e.target).closest("tr").data('user');
    jsonAjax({
        url: '/user/' + user.id,
        method: "delete"
    });
}

function btnEditUserClicked(e) {
    var user = $(e.target).closest("tr").data('user');
    redirectContentDiv("/user/" + user.id);
}

function editUser(user) {
    var $form = $("#editUserForm");
    $form.find("#name").val(user.name);
    $form.find("#email").val(user.email);
    $form.find("#role").val(user.role);
}

function saveOk() {
    alert("Saved!")
}

var initialRoute = null;

function setupIndex() {
    $("body").on("submit", ".spa_form", submitSpaForm);
    $("body").on("click", "a", linkClicked);
    $("body").on("keydown", "input.newTzName", newTzNameSelected);
    $("body").on("click", ".btnDelTimezone", btnDelTimezoneClicked);
    $("body").on("click", ".btnDelUser", btnDelUserClicked);
    $("body").on("click", ".btnEditUser", btnEditUserClicked);
    if (initialRoute) {
        redirectContentDiv(initialRoute);
    }
    reloadMenus();
}

$(document).ready(setupIndex);