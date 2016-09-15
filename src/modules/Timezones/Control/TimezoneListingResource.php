<?php

namespace MDTimezone\Timezones\Control;

class TimezoneListingResource extends \Resourceful\RestfulWebAppResource {

    use \MDTimezone\User\Model\MustBeLoggedIn;

    public $timezones;

    /**
     * User used for listing/creating timezones.
     */
    protected function userListing() {
        return $this->auth->currentUser();
    }

    public function get() {                
        $userTimezones = $this->timezones->listUserTimezones($this->userListing()->getId());
        return ['timezones' => $userTimezones];
    }

    public function post() {        
        $tz = $this->timezones->newTimezone($this->data, $this->userListing());
        return $tz;
    }
    
}