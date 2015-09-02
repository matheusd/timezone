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
        $user = $this->users->newUser($this->data);
        return [];
    }
} 