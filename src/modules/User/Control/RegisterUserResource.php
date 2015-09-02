<?php

namespace ToptalTimezone\User\Control;

class RegisterUserResource extends \Resourceful\RestfulWebAppResource {

    use \Resourceful\GeneratesTemplatedHtml;    
    
    public function __construct() {
        $this->CONTENT_VIEWS = [__DIR__."/../View/register.php"];
    }

    public function get() {        
        return [];
    }
    
    public function post() {
        return [];
    }
} 