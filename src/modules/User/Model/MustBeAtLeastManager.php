<?php

namespace ToptalTimezone\User\Model;

trait MustBeAtLeastManager {

    public function fbef_mustBeAtLeastManager() {
        if (!$this->auth->currentUserIsManager() && !$this->auth->currentUserIsAdmin()) {
            throw new UnauthorizedUserActionException("User must be manager or admin to perform this action");
        }
    }
}