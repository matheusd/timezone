<?php

namespace ToptalTimezone\User\Model;

trait MustBeAdmin {

    public function fbef_mustBeAtLeastManager() {
        if (!$this->auth->currentUserIsAdmin()) {
            throw new UnauthorizedUserActionException("User must be admin to perform this action");
        }
    }
}
