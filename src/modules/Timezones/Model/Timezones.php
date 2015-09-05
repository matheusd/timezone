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

    public function listUserTimezones($userId) {
        //$dql = "SELECT b, e, r FROM Bug b JOIN b.engineer e JOIN b.reporter r ".
        //       "WHERE b.status = 'OPEN' AND e.id = ?1 OR r.id = ?1 ORDER BY b.created DESC";
        $dql = "SELECT tz FROM tt:Timezone tz join tt:User u "
                . " where u.id = ?1";        

        return $this->entityManager->createQuery($dql)
                             ->setParameter(1, $userId)                             
                             ->getResult();
    }
}