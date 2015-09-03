<?php

namespace ToptalTimezone\User\Model;

trait MustBeLoggedIn {

    public function fbef_mustBeLoggedIn() {
        error_log('here');
        if (!$this->auth->isLoggedIn()) {
            throw new UserNotLoggedInException("User must be logged in to perform this action");
        }
    }
}