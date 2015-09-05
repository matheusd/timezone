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
    redirectContentDiv(href);
    e.preventDefault();
    e.stopPropagation();
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
        url: '/timezones',
        method: 'POST',
        jsonData: {name: $(e.target).val()}
    });
}


function setupIndex() {
    $("body").on("submit", ".spa_form", submitSpaForm);
    $("body").on("click", "a", linkClicked);
    $("body").on("keydown", "input.newTzName", newTzNameSelected);
    redirectContentDiv(initialRoute);
    reloadMenus();
}

$(document).ready(setupIndex);