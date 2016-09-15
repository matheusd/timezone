<?php

namespace MDTimezone\Timezones\Control;

/**
 * Resource for editing the timezones of an user other than the current
 * logged-in user.
 */
class EditUserTimezoneResource extends EditTimezoneResource {

    use \MDTimezone\User\Model\MustBeAdmin;
    use \MDTimezone\User\Control\CheckAuthUserFromParameter;


}
