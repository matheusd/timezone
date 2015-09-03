<?php

namespace ToptalTimezone\User\Control;

class LogoutResource extends \Resourceful\RestfulWebAppResource {

    use \Resourceful\GeneratesTemplatedHtml;        
    use \ToptalTimezone\User\Model\MustBeLoggedIn;

    public function get() {        
        $this->CONTENT_VIEWS = [__DIR__."/../view/logout.php"];
        return [];
    }
    
    public function post() {        
        $this->auth->logout();
        return ['status' => 'ok'];
    }
}