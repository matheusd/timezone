<?php

namespace MDTimezone\User\Control;

trait CheckAuthUserFromParameter {

    protected function userIdParameterName() {
        return 'id';
    }

    protected function fbef_mustHaveUser() {        
        $pmtName = $this->userIdParameterName();
        $this->user = $this->users->userById($this->parameters[$pmtName]);
        if (!$this->user) {
            throw new UserNotFoundException("User " . $this->parameters[$pmtName] . ' not found');
        }

        if ($this->user->getId() == $this->auth->currentUserId()) {
            return;
        }

        if ($this->auth->currentUserIsAdmin()) {
            return;
        }

        if ($this->user->getRole() >= $this->auth->currentUser()->getRole()) {
            throw new UnauthorizedModifyUserException("You cannot modify data from an user with same or higher role");
        }

    }
}
