<?php

namespace MDTimezone\User\Control;

class IsLoggedInResource extends \Resourceful\RestfulWebAppResource {

    use \Resourceful\GeneratesTemplatedHtml;        

    public function get() {        
        if (!$this->auth->isLoggedIn()) {
            throw new LoginAuthException("User not logged in");
        }
        
        return ['status' => 'ok'];
    }
        
}