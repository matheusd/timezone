<?php

namespace ToptalTimezone\User\Model;

class Auth {

    public function login($user) {
        $this->session['userId'] = $user->getId();
    }
    
    public function logout() {
        unset($this->session['userId']);
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

    public function currentUserId() {
        return $this->session['userId'];
    }

    public function currentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }

        return $this->entityManager->getRepository('ToptalTimezone\Orm\User')
                 ->findOneBy(array('id' => $this->session['userId']));
    }

    public function currentUserIsManager() {
        return $this->currentUser()->isManager();
    }

    public function currentUserIsAdmin() {
        return $this->currentUser()->isAdmin();
    }

}