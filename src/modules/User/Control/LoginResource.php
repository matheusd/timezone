<?php

namespace MDTimezone\User\Control;

class LoginResource extends \Resourceful\RestfulWebAppResource {

    use \Resourceful\GeneratesTemplatedHtml;        

    public function get() {        
        if ($this->auth->isLoggedIn()) {
            return new \Zend\Diactoros\Response\RedirectResponse("/timezones");
        }
        $this->CONTENT_VIEWS = [__DIR__."/../view/login.php"];
        return [];
    }
    
    public function post() {
        if (!$this->auth->mayLogin(@$this->data->email, @$this->data->password)) {
            throw new LoginAuthException("Inexistent user or wrong password");
        }
        $this->auth->login($this->users->userByEmail($this->data->email));
        return ['status' => 'ok'];
    }
}