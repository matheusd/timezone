<?php

namespace ToptalTimezone\User\Model;

class Auth {

    public function login($user) {
        $this->session['userId'] = $user->getId();
    }
    
    public function mayLogin($email, $password) {
        $user = $this->entityManager->getRepository('ToptalTimezone\Orm\User')
                 ->findOneBy(array('email' => $email));
        if (!$user) return false;
        return $user->getPassword() == $password;
    }
    
    public function isLoggedIn() {
        return isset($this->session['userId']);
    }

}