<?php

namespace ToptalTimezone\Timezones\Model;

use \ToptalTimezone\Orm\Timezone;

class Timezones
{
    public function newTimezone($tzData, $user) {
        $tz = new Timezone();
        $tz->dataFromObj($tzData);
        $tz->assignToUser($user);

        $this->entityManager->persist($tz);
        $this->entityManager->flush();

        return $tz;
    }

    public function timezoneById($id) {
        return $this->entityManager->getRepository('ToptalTimezone\Orm\Timezone')
             ->findOneBy(array('id' => $id));
    }

    public function listUserTimezones($userId) {
        $dql = "SELECT tz FROM tt:Timezone tz join tt:User u "
                . " where u.id = ?1";        

        return $this->entityManager->createQuery($dql)
                             ->setParameter(1, $userId)                             
                             ->getResult();
    }

    public function deleteTimezone($timezone) {
        $this->entityManager->remove($timezone);
        $this->entityManager->flush();
    }
}