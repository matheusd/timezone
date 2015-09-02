<?php

namespace ToptalTimezone\Orm;

trait ValidatableObject {

    public function isValid($validator) {
        $violations = $validator->validate($this);
        if (count($violations) == 0) return;

        throw new ValidationException($violations);        
    }
    
}