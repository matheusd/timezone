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
    use CheckAuthUserFromParameter;

    public $users;

    protected $user;

    public function checkCantModifySelf() {
        if ($this->user->getId() == $this->auth->currentUserId()) {
            throw new CannotDeleteSelfException("You cannot modify yourself (use your profile page).");
        }
    }    
       
    public function get() {
        $this->checkCantModifySelf();
        $this->CONTENT_VIEWS = [__DIR__."/../view/editUser.php"];
        return ['user' => $this->user];
    }

    public function post() {
        $this->checkCantModifySelf();
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

        $user = $this->users->modifyUser($this->user, $this->data);        
        return [];
    }

    public function delete() {
        $this->checkCantModifySelf();
        $this->users->deleteUser($this->user);

        return [];
    }

}
