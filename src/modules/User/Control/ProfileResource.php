<?php

namespace MDTimezone\User\Control;

class ProfileResource extends \Resourceful\RestfulWebAppResource {
    
    use \MDTimezone\User\Model\MustBeLoggedIn;

    public function get() {
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

        if (@$this->data->role) {
            //seceurity protection to avoid user spoofing a role change
            unset($this->data->role);
        }

        $user = $this->users->modifyUser($this->auth->currentUser(), $this->data);
        return [];
    }
}