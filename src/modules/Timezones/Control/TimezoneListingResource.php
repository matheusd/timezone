<?php

namespace ToptalTimezone\Timezones\Control;

class TimezoneListingResource extends \Resourceful\RestfulWebAppResource {

    use \Resourceful\GeneratesTemplatedHtml;        
    use \ToptalTimezone\User\Model\MustBeLoggedIn;

    public function get() {        
        $this->CONTENT_VIEWS = [__DIR__."/../view/timezones.php"];
        return [];
    }
    
}