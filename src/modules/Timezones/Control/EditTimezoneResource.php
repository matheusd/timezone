<?php

namespace MDTimezone\Timezones\Control;

class EditTimezoneResource extends \Resourceful\RestfulWebAppResource {
    
    public $timezones;

    public function fbef_checkUserPermission() {
        if (!$this->auth->isLoggedIn()) {
            throw new \MDTimezone\User\Model\UserNotLoggedInException("Must be logged in to modify timezone");
        }

        $this->tz = $this->timezones->timezoneById($this->parameters['tzId']);
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

    public function post() {
        if (@$this->data->name) {
            //can only change the name for timezone
            $this->timezones->modifyTimezone($this->tz, (Object) ['name' => $this->data->name]);
        }
        return $this->tz;
    }

    public function delete() {
        
        $this->timezones->deleteTimezone($this->tz);
        
        return $this->tz;
    }

}