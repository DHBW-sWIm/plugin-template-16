<?php

class camunda_file {
    var $value;
    var $type;
    var $valueInfo;

    /**
     * CamundaVar constructor.
     *
     * @param $value
     * @param $type
     * @param $valueInfo
     */
    public function __construct($filename, $mimeType, $base64) {
        $this->value = $base64;
        $this->type = 'File';
        $this->valueInfo->filename = $filename;
        $this->valueInfo->mimeType = $mimeType;
    }
}
