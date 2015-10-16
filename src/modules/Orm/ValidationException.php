<?php

namespace MDTimezone\Orm;

class ValidationException 
    extends \Resourceful\Exception\BadRequestException 
    implements \Resourceful\Exception\WebAppDataException 
    
{
    protected $violations;

    public function __construct($violations) {
        parent::__construct("Validation Exception");
        $this->violations = $violations;
    }

    public function exceptionData() {
        $res = [];
        foreach ($this->violations as $v) {
            $res[] = $v->getMessage();
        }
        return ['violations' => $res];
    }

}
