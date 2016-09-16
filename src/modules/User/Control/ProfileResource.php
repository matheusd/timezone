<?php

namespace MDTimezone\User\Control;

class ProfileResource extends \Resourceful\RestfulWebAppResource {
    
    use \MDTimezone\User\Model\MustBeLoggedIn;

    public function get() {
        return ['user' => $this->auth->currentUser()];
    }

    public function post() {
        if (property_exists($this->data, 'password') && property_exists($this->data, 'password2')) {
            if (($this->data->password != $this->data->password2)) {
                throw new PasswordException("Passwords don't match");
            }
        }

        if (property_exists($this->data, 'email')) {
            $user = $this->users->userByEmail($this->data->email);
            if ($user && ($user->getId() != $this->auth->currentUserId())) {
                throw new UserExistsException("User with provided email already exists");
            }
        }
                
        if (property_exists($this->data, 'role')) {
            //security protection to avoid user spoofing a role change
            unset($this->data->role);
        }

        $user = $this->users->modifyUser($this->auth->currentUser(), $this->data);
        return [];
    }
}