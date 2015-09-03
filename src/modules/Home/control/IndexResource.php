<?php

namespace ToptalTimezone\Home\Control;

class IndexResource extends \Resourceful\RestfulWebAppResource {

    use \Resourceful\GeneratesTemplatedHtml;    
        
    public function get() {        
        $this->CONTENT_VIEWS = [__DIR__."/../view/index.php"];
        return ['isLoggedIn' => $this->auth->isLoggedIn()];
    }
}