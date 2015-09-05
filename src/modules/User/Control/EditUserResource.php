<?php

namespace ToptalTimezone\User\Control;

/**
 * Description of EditUserResource
 *
 * @author matheus
 */
class EditUserResource extends \Resourceful\RestfulWebAppResource {

    use \ToptalTimezone\User\Model\MustBeLoggedIn;
    use \Resourceful\GeneratesTemplatedHtml;

    public $users;

    protected $user;
    
    protected function fbef_mustHaveUser() {
        $this->user = $this->users->userById($this->parameters['id']);
        if (!$this->user) {
            throw new UserNotFoundException("User " . $this->parameters['id'] . ' not found');
        }

    }

    public function get() {
        $this->CONTENT_VIEWS = [__DIR__."/../view/editUser.php"];
        return ['user' => $this->user];
    }

    public function post() {
        if (@($this->data->password != $this->data->password2)) {
            throw new PasswordException("Passwords don't match");
        }
        
        $user = $this->users->userByEmail(@$this->data->email);
        if ($user) {
            throw new UserExistsException("User with provided email already exists");
        }
        if (!@$this->data->password) {
            unset($this->data->password);
        }

        $user = $this->users->modifyUser($this->user, $this->data);        
        return [];
    }

    public function delete() {        
        if ($this->user->getId() == $this->auth->currentUserId()) {
            throw new CannotDeleteSelfException("You cannot delete yourself from the system.");
        }

        $this->users->deleteUser($this->user);

        return [];
    }

}
