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
        $res = $user->passwordMatches($password);
        if (!$res) return false;
        if ($user->passwordNeedsRehash()) {
            $user->setPassword($password);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        return true;
    }
    
    public function isLoggedIn() {
        if (!isset($this->session['userId'])) return false;
        $user = $this->entityManager->getRepository('ToptalTimezone\Orm\User')
                 ->findOneBy(array('id' => $this->session['userId']));
        if (is_null($user)) return false;
        return true;
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
        if (!$this->isLoggedIn()) return false;
        return $this->currentUser()->isManager();
    }

    public function currentUserIsAdmin() {
        if (!$this->isLoggedIn()) return false;
        return $this->currentUser()->isAdmin();
    }

}