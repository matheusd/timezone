<h1>Timezones</h1>

<?php if ($user) {
    echo "<h3>For user " . $user->getName() . "</h3>";
}
?>

<div id="timezones">
    
</div>

<div class="row timezone">
    <input type='hidden' id='newTzFormUri' value='<?=$uri?>'>
    <div class="col-sm-4">        
        <input type="text" class="form-control newTzName selectize-input" placeholder="Timezone city or name" name="tzCity" required="required">        
        <div id="tgt"></div>
    </div>
</div>

<div style="display: none">
    <div class="row timezone" id="modelTimezone">
        <div class="col-md-6 timezoneData">
            <div class="col-sm-4 tzEditData" style="display:none">
                <input type="text" class="form-control editTzName selectize-input" placeholder="Timezone city or name" name="tzCity" required="required">
            </div>
            <div class="col-sm-4 tzData">
                <div class="tzName">Europe/London</div>
                <div class="tzDescr">BRT / GMT +03:00</div>
            </div>
            <div class="col-sm-6">
                <div class="tzTime">
                    Sat, Oct 05 2015 - 10:40 am
                </div>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-default pull-right btnDelTimezone"><i class="glyphicon glyphicon-remove"></i></button>
            </div>
        </div>
    </div>
</div>


<script>
    setTimeout(function() {reloadTimezones(<?= json_encode($timezones)?>)}, 200);
</script>