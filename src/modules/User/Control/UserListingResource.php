<?php

namespace MDTimezone\User\Control;

class UserListingResource extends \Resourceful\RestfulWebAppResource {

    use \Resourceful\GeneratesTemplatedHtml;
    use \MDTimezone\User\Model\MustBeAtLeastManager;

    public $users;

    public function get() {
        $this->CONTENT_VIEWS = [__DIR__."/../view/users.php"];
        $level = $this->auth->currentUserIsAdmin() ? 1000 : $this->auth->currentUser()->getRole();
        $users = $this->users->listUsers($level);
        return ['users' => $users, 'currentUser' => $this->auth->currentUser()];
    }

    public function post() {
        if (@($this->data->password != $this->data->password2)) {
            throw new PasswordException("Passwords don't match");
        }

        $user = $this->users->userByEmail(@$this->data->email);
        if ($user && ($user->getId() != $this->user->getId())) {
            throw new UserExistsException("User with provided email already exists");
        }
        if (!@$this->data->password) {
            unset($this->data->password);
        }

        if (@$this->data->role) {
            //protection against evelevation of privileges
            if ($this->data->role >= $this->auth->currentUser()->getRole() && !($this->auth->currentUserIsAdmin())) {
                unset($this->data->role);
            }
        }
        
        $user = $this->users->newUser($this->data);
        return ['id' => $user->getId()];
    }
    
}