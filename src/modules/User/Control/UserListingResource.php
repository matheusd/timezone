<?php

namespace ToptalTimezone\User\Control;

class UserListingResource extends \Resourceful\RestfulWebAppResource {

    use \Resourceful\GeneratesTemplatedHtml;
    use \ToptalTimezone\User\Model\MustBeAtLeastManager;

    public $users;

    public function get() {
        $this->CONTENT_VIEWS = [__DIR__."/../view/users.php"];
        $users = $this->users->listUsers($this->auth->currentUser()->getRole());
        return ['users' => $users];
    }
    
}