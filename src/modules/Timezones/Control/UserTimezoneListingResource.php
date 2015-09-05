<?php

namespace ToptalTimezone\Timezones\Control;

class UserTimezoneListingResource extends \Resourceful\RestfulWebAppResource {

    use \Resourceful\GeneratesTemplatedHtml;
    use \ToptalTimezone\User\Model\MustBeAdmin;
    use \ToptalTimezone\User\Control\CheckAuthUserFromParameter;

    public $timezones;


    public function get() {
        $this->CONTENT_VIEWS = [__DIR__."/../view/timezones.php"];        
        $userTimezones = $this->timezones->listUserTimezones($this->user->getId());
        return ['timezones' => $userTimezones, 'uri' => $this->request->getUri()->getPath()];
    }

    public function post() {
        error_log('addint to ' . $this->user->getId());
        $tz = $this->timezones->newTimezone($this->data, $this->user);
        return ['id' => $tz->getId()];
    }

}