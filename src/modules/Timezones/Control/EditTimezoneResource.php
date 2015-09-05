<?php

namespace ToptalTimezone\Timezones\Control;

class EditTimezoneResource extends \Resourceful\RestfulWebAppResource {

    use \ToptalTimezone\User\Model\MustBeLoggedIn;

    public $timezones;

    public function fbef_checkUserPermission() {
        $this->tz = $this->timezones->timezoneById($this->parameters['id']);
        if (!$this->tz) {
            throw new TimezoneNotFoundException("Timezone " . $this->parameters['id'] . ' not found');
        }

        $userTz = $this->tz->getUser();
        if ($userTz->getId() != $this->auth->currentUserId() &&
            $userTz->getRole() >= $this->auth->currentUser()->getRole())
        {
            throw new UnauthorizedModifyTimezoneException("Cannot modify timezone from user with higher role than you.");
        }
    }

    public function delete() {
        
        $this->timezones->deleteTimezone($this->tz);
        
        return [];
    }

}