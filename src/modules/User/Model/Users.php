<?php

namespace ToptalTimezone\User\Model;

use \ToptalTimezone\ORM\User;

class Users {
    
    public $entityManager;

    public function newUser($userData) {
        $user = new User();
        
        $user->setName($userData->name);
        $user->setEmail($userData->email);
        $user->setPassword($userData->password);
        
        $user->isValid($this->validator);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }
    
    public function userByEmail($email) {
        return $this->entityManager->getRepository('ToptalTimezone\Orm\User')
                 ->findOneBy(array('email' => $email));
    }

    public function listUsers() {
        return $this->entityManager->getRepository('ToptalTimezone\Orm\User')
                 ->findAll();
    }

    public function userById($id) {
        return $this->entityManager->getRepository('ToptalTimezone\Orm\User')
             ->findOneBy(array('id' => $id));
    }

    public function deleteUser($user) {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

}
