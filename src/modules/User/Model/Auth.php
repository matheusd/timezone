<?php

namespace MDTimezone\User\Model;

class Auth {

    const PERM_IS_LOGGED_IN = 1;
    const PERM_IS_ADMIN = 2;
    const PERM_IS_MANAGER = 3;


    public function login($user) {
        $this->session['userId'] = $user->getId();
    }
    
    public function logout() {
        unset($this->session['userId']);
    }
    
    public function mayLogin($email, $password) {
        $user = $this->entityManager->getRepository('MDTimezone\Orm\User')
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
        $user = $this->entityManager->getRepository('MDTimezone\Orm\User')
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

        return $this->entityManager->getRepository('MDTimezone\Orm\User')
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

    /**
     * Whether the user has the given permission.
     * @param type $perm
     */
    public function hasPermission($perm) {
        switch ($perm) {
            case self::PERM_IS_LOGGED_IN: return $this->isLoggedIn();
            case self::PERM_IS_ADMIN: return $this->currentUserIsAdmin();
            case self::PERM_IS_MANAGER: return $this->currentUserIsManager();
        }
    }

    /**
     * Check if the permission bits follow a given permission array.
     * A permission array is an array of ANDed permissions ORed together.
     * Eg:
     * [[PERM_IS_ADMIN], [PERM_IS_LOGGED_IN, PERM_IS_MANAGER]]
     * means
     * PERM_IS_ADMIN or (PERM_IS_LOGGED_IN and PERM_IS_MANAGER)
     * @param type $perms
     */
    public function hasFullPermission($perms) {
        foreach ($perms as $orClause) {
            $hasPerm = true;
            foreach ($orClause as $permBit) {
                $hasPerm = $hasPerm && $this->hasPermission($permBit);
                if (!$hasPerm) break;
            }
            if ($hasPerm) return true;
        }
        return false;
    }


}