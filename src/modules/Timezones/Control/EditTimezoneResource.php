<?php

namespace ToptalTimezone\Timezones\Control;

class EditTimezoneResource extends \Resourceful\RestfulWebAppResource {

    use \ToptalTimezone\User\Model\MustBeLoggedIn;

    public $timezones;

    public function delete() {
        $tz = $this->timezones->timezoneById($this->parameters['id']);        
        if (!$tz) {
            throw new TimezoneNotFoundException("Timezone " . $this->parameters['id'] . ' not found');
        }
        $this->timezones->deleteTimezone($tz);
        
        return [];
    }

}