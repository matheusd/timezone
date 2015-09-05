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
        if (@($this->data->password != $this->data->password2)) {
            throw new PasswordException("Passwords don't match");
        }

        $user = $this->users->userByEmail(@$this->data->email);
        if ($user && ($user->getId() != $this->auth->currentUserId())) {
            throw new UserExistsException("User with provided email already exists");
        }
        if (!@$this->data->password) {
            unset($this->data->password);
        }

        $user = $this->users->modifyUser($this->auth->currentUser(), $this->data);
        return [];
    }
}