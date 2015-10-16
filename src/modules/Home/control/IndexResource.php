<?php

namespace MDTimezone\Home\Control;

class IndexResource extends \Resourceful\RestfulWebAppResource {

    use \Resourceful\GeneratesTemplatedHtml;    
        
    public function get() {        
        if ($this->request->getHeaderLine('X-Requested-With') == 'XMLHttpRequest') {
            if ($this->auth->isLoggedIn()) {
                return new \Zend\Diactoros\Response\RedirectResponse("/timezones");
            } else {
                return new \Zend\Diactoros\Response\RedirectResponse("/user/login");
            }
        }
        $this->CONTENT_VIEWS = [__DIR__."/../view/index.php"];
        return ['isLoggedIn' => $this->auth->isLoggedIn()];
    }
}