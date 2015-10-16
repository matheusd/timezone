<?php

namespace MDTimezone\Templater;


class SampleTemplaterFactory {

    public $globalContext;
    
    public $request;
    
    public function newTemplater() {
        $t = new SampleTemplater();
        $t->globalContext = $this->globalContext;
        $reqWith = $this->request->getHeaderLine('X-Requested-With');        
        if ($reqWith == 'XMLHttpRequest') {
            $t->addDefaultTemplate = false;
        }
        $t->setup();
        return $t;
    }

}
