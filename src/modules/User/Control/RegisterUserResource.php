<?php

namespace ToptalTimezone\User\Control;

class RegisterUserResource extends \Resourceful\RestfulWebAppResource {

    use \Resourceful\GeneratesTemplatedHtml;    
        
    public $users;

    public function get() {        
        $this->CONTENT_VIEWS = [__DIR__."/../View/register.php"];        
        return [];
    }
    
    public function post() {        
        if (@($this->data->password != $this->data->password2)) {
            throw new PasswordException("Passwords don't match");
        }
        if (strlen(@$this->data->password) < 6) {
            throw new PasswordException("Password length is too small (must be at least 6 characters)");
        }
                
        $user = $this->users->newUser($this->data);
        return ['status' => 'ok'];
    }
} 