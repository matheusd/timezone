<?php

namespace ToptalTimezone\User\Control;

class ProfileResource extends \Resourceful\RestfulWebAppResource {

    use \Resourceful\GeneratesTemplatedHtml;
    use \ToptalTimezone\User\Model\MustBeLoggedIn;

    public function get() {
        $this->CONTENT_VIEWS = [__DIR__."/../view/profile.php"];
        return ['user' => $this->auth->currentUser()];
    }

    public function post() {
        return [];
    }
}