<h1>Timezones</h1>

<?php if ($user) {
    echo "<h3>For user " . $user->getName() . "</h3>";
}
?>

<div class="row">
    <div class="col-md-6">
        <div id="timezones">

            <div ng-repeat="tz in timezones">
                <div class="row timezone" id="modelTimezone">
                    <div class="col-sm-4 tzEditData" ng-if="tz.inEditMode">
                        <input id="edtTz{{tz.id}}" type="text" class="form-control editTzName selectize-input" 
                               placeholder="Timezone city or name" name="tzCity"
                               required="required" rebind-selectize="1">
                    </div>
                    <div class="col-sm-4 tzData" ng-click="changeEditMode(tz)" ng-if="!tz.inEditMode">
                        <div class="tzName">{{tz.name}}</div>
                        <div class="tzDescr">{{tz.abbr}} / GMT {{tz.gmtOffset}}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="tzTime">
                            {{tz.timeInTz}}
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-default btnDelTimezone"><i class="glyphicon glyphicon-remove"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 timezone">
        <input type='hidden' id='newTzFormUri' value='<?=$uri?>'>
        <div class="col-sm-12">
            <input type="text" class="form-control newTzName selectize-input" placeholder="Timezone city or name" name="tzCity" required="required">
            <div id="tgt"></div>
        </div>
    </div>
</div>
