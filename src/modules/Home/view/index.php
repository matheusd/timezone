

<div id="content-div">

</div>

<script>    
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
                alert("Success!");
            },
            error: function (xhr) {
                var json = JSON.parse(xhr.responseText);
                if (json && json.status == 'error') {
                    if (json.class = 'ToptalTimezone\\ValidationException') {
                        var msg = json.violations.join("\n");
                        alert(msg);
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
        $("#content-div").load('/user/new');        
    }
</script>

<?php $templater->addFooterContent("<script>$(document).ready(setupIndex)</script>");