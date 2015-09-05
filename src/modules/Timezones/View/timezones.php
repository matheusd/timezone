
<div id="timezones">
    
</div>

<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <input type="text" class="form-control newTzName" placeholder="Timezone city or name" name="tzCity" required="required">
        </div>
    </div>
</div>

<script>
    reloadTimezones(<?= json_encode($timezones)?>);
</script>