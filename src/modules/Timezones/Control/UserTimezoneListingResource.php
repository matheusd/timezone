<?php

namespace MDTimezone\Timezones\Control;

class UserTimezoneListingResource extends \Resourceful\RestfulWebAppResource {

    use \Resourceful\GeneratesTemplatedHtml;
    use \MDTimezone\User\Model\MustBeAdmin;
    use \MDTimezone\User\Control\CheckAuthUserFromParameter;

    public $timezones;


    public function get() {
        $this->CONTENT_VIEWS = [__DIR__."/../view/timezones.php"];        
        $userTimezones = $this->timezones->listUserTimezones($this->user->getId());
        return ['timezones' => $userTimezones, 'uri' => $this->request->getUri()->getPath(),
            'user' => $this->user];
    }

    public function post() {
        $tz = $this->timezones->newTimezone($this->data, $this->user);
        return ['id' => $tz->getId()];
    }

}