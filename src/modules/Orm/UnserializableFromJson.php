<?php

namespace MDTimezone\Orm;

trait UnserializableFromJson {

    protected function listSetters() {
        $methods = get_class_methods($this);
        $setters = array_filter($methods, function ($i) {
            return stripos($i, "set") === 0;
        });
        return $setters;
    }

    public function dataFromObj($obj) {
        $setters = $this->listSetters();
        foreach ($setters as $method) {
            $attrName = substr($method, 3);
            if (!$attrName) continue;
            $attrName[0] = strtolower($attrName[0]);
            if (property_exists($obj, $attrName)) {
                $this->$method($obj->$attrName);
            }
        }
    }

}