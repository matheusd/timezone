<?php

namespace ToptalTimezone\User\Control;

/**
 * Description of EditUserResource
 *
 * @author matheus
 */
class EditUserResource extends \Resourceful\RestfulWebAppResource {

    use \ToptalTimezone\User\Model\MustBeLoggedIn;

    public $timezones;

    public function delete() {
        $user = $this->users->userById($this->parameters['id']);
        if (!$user) {
            throw new UserNotFoundException("user " . $this->parameters['id'] . ' not found');
        }

        if ($user->getId() == $this->auth->currentUserId()) {
            throw new CannotDeleteSelfException("You cannot delete yourself from the system.");
        }

        $this->users->deleteUser($user);

        return [];
    }

}
