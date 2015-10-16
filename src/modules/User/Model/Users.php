<?php

namespace MDTimezone\User\Model;

use \MDTimezone\ORM\User;

class Users {
    
    public $entityManager;

    public function newUser($userData) {
        $user = new User();
        $this->modifyUser($user, $userData);
        return $user;
    }
    
    public function userByEmail($email) {
        return $this->entityManager->getRepository('MDTimezone\Orm\User')
                 ->findOneBy(array('email' => $email));
    }

    public function listUsers($maximumRole=0) {
        $dql = "SELECT u FROM tt:User u "
                . "WHERE u.role < ?1"
                . "ORDER BY u.id ";
        $query = $this->entityManager->createQuery($dql)->setParameter(1, $maximumRole);
        return $query->getArrayResult();
    }

    public function userById($id) {
        return $this->entityManager->getRepository('MDTimezone\Orm\User')
             ->findOneBy(array('id' => $id));
    }

    public function deleteUser($user) {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function modifyUser($user, $userData) {
        $user->dataFromObj($userData);

        $user->isValid($this->validator);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

}
