<?php

class camunda_var {
    var $value;
    var $type;

    /**
     * CamundaVar constructor.
     *
     * @param $value
     * @param $type
     */
    public function __construct($value, $type) {
        $this->value = $value;
        $this->type = $type;
    }
}
