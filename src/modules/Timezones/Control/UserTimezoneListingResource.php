<?php

namespace MDTimezone\Timezones\Control;

class UserTimezoneListingResource extends TimezoneListingResource {

    use \MDTimezone\User\Model\MustBeAdmin;
    use \MDTimezone\User\Control\CheckAuthUserFromParameter;

    public $timezones;

    protected function userListing() {
        return $this->user;
    }
    
}