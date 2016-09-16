<?php

namespace MDTimezone\User\Control;

use \MDTimezone\Exceptions\UnspecifiedFieldException;

/**
 * Description of EditUserResource
 *
 * @author matheus
 */
class EditUserResource extends \Resourceful\RestfulWebAppResource {

    use \MDTimezone\User\Model\MustBeLoggedIn;    
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
        return $this->user;
    }

    public function post() {
        $this->checkCantModifySelf();
        if (property_exists($this->data, 'password') && property_exists($this->data, 'password2')) {
            if (($this->data->password != $this->data->password2)) {
                throw new PasswordException("Passwords don't match");
            }
        }

        if (property_exists($this->data, 'email')) {
            $user = $this->users->userByEmail($this->data->email);
            if ($user && ($user->getId() != $this->user->getId())) {
                throw new UserExistsException("User with provided email already exists");
            }
        }        

        if (property_exists($this->data, 'role')) {
            //protection against evelevation of privileges
            if ($this->data->role >= $this->auth->currentUser()->getRole() && !($this->auth->currentUserIsAdmin())) {
                unset($this->data->role);
            }
        }        

        $user = $this->users->modifyUser($this->user, $this->data);        
        return $user;
    }

    public function delete() {
        $this->checkCantModifySelf();
        $this->users->deleteUser($this->user);

        return ['status' => 'ok'];
    }

}
