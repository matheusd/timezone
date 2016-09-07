<?php

namespace MDTimezone\User\Control;

class LoginResource extends \Resourceful\RestfulWebAppResource {
    
    public function post() {
        if (!$this->auth->mayLogin(@$this->data->email, @$this->data->password)) {
            throw new LoginAuthException("Inexistent user or wrong password");
        }
        $this->auth->login($this->users->userByEmail($this->data->email));
        return ['status' => 'ok'];
    }
}