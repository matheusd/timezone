<?php

namespace ToptalTimezone\Exceptions\Control;

class ExceptionResource extends \Resourceful\Exception\ExceptionResponseBuilder {
    use \Resourceful\GeneratesTemplatedHtml;

    protected function addContentViews($templater, $responseData) {
        $cls = str_replace('\\', '_', $responseData['class']);
        error_log("xxx $cls");
        if (file_exists(__DIR__."/../View/$cls.php")) {
            $templater->addContentView(__DIR__."/../View/$cls.php", $responseData);
        } else {
            $templater->addContentContent("<pre>".print_r($responseData, true)."</pre>");
        }
    }
}