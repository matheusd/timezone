
<div id="timezones">
    
</div>

<div class="row">
    <input type='hidden' id='newTzFormUri' value='<?=$uri?>'>
    <div class="col-sm-3">
        <div class="form-group">
            <input type="text" class="form-control newTzName" placeholder="Timezone city or name" name="tzCity" required="required">
        </div>
    </div>
</div>

<script>
    setTimeout(function() {reloadTimezones(<?= json_encode($timezones)?>)}, 1000);
</script>