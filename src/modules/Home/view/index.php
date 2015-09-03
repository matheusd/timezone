

<div id="content-div">

</div>

<script>    
    function redirectContentDiv(url) {
        $("#content-div").empty().load(url);        
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
    
    function setupIndex() {
        $("body").on("submit", ".spa_form", submitSpaForm);
        redirectContentDiv("<?= $isLoggedIn ? '/timezones' : '/user/login'?>");
    }
</script>

<?php $templater->addFooterContent("<script>$(document).ready(setupIndex)</script>");