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

function submitSpaForm(e) {
    e.preventDefault();
    e.stopPropagation();
    $form = $(e.target);
    $.ajax({method: $form.attr('method'),
        url: $form.attr('action'),            
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: JSON.stringify($form.serializeJSON()),
        success: function (data) {
            if ($form.attr('spaAfterSubmit')) {
                window[$form.attr('spaAfterSubmit')].apply(data);
            }
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


function setupIndex() {
    $("body").on("submit", ".spa_form", submitSpaForm);
    $("body").on("click", "a", linkClicked);
    redirectContentDiv(initialRoute);
    reloadMenus();
}

$(document).ready(setupIndex);