<?php

namespace ToptalTimezone\Templater;

class SampleTemplater extends \Resourceful\WebAppTemplater {

    public $addDefaultTemplate = true;

    public function setup() {
        if ($this->addDefaultTemplate) {
            $this->addFooterView(__DIR__."/templates/footer.php", []);
            $this->addHeaderView(__DIR__."/templates/header.php", []);
        }
    }    

}