<?php

namespace MDTimezone\Timezones\Control;

class TimezoneListingResource extends \Resourceful\RestfulWebAppResource {

    use \Resourceful\GeneratesTemplatedHtml;        
    use \MDTimezone\User\Model\MustBeLoggedIn;

    public $timezones;

    public function get() {        
        $this->CONTENT_VIEWS = [__DIR__."/../view/timezones.php"];
        $userTimezones = $this->timezones->listUserTimezones($this->auth->currentUserId());
        return ['timezones' => $userTimezones, 'uri' => '/timezones', 'user' => null];
    }

    public function post() {        
        $tz = $this->timezones->newTimezone($this->data, $this->auth->currentUser());
        return ['id' => $tz->getId()];
    }
    
}