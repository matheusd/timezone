<?php

namespace ToptalTimezone\User\Control;

class MenusResource extends \Resourceful\RestfulWebAppResource {

    use \Resourceful\GeneratesTemplatedHtml;

    public function get() {
        if (!$this->auth->isLoggedIn()) {
        } else if ($this->auth->currentUserIsAdmin() || $this->auth->currentUserIsManager()) {
            $this->CONTENT_VIEWS = [__DIR__."/../view/managerMenus.php"];
        } else {
            $this->CONTENT_VIEWS = [__DIR__."/../view/loggedInMenus.php"];
        } 
                
        return [];
    }
        
}