<?php

namespace MDTimezone\User\Model;

trait MustBeLoggedIn {

    public function fbef_mustBeLoggedIn() {
        
        if (!$this->auth->isLoggedIn()) {
            throw new UserNotLoggedInException("User must be logged in to perform this action");
        }
    }
}