<?php

namespace MDTimezone\User\Control;

class LogoutResource extends \Resourceful\RestfulWebAppResource {

    use \Resourceful\GeneratesTemplatedHtml;        
    use \MDTimezone\User\Model\MustBeLoggedIn;

    public function get() {        
        $this->CONTENT_VIEWS = [__DIR__."/../view/logout.php"];
        return [];
    }
    
    public function post() {        
        $this->auth->logout();
        return ['status' => 'ok'];
    }
}