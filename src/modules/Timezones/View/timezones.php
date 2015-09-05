
<div id="newTimezone" class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <input type="text" class="form-control newTzName" placeholder="Timezone city or name" name="tzCity" required="required">
        </div>
    </div>
</div>

<script>
    var timezones = <?= json_encode($timezones)?>
</script>