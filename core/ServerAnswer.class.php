<?php
/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 24.06.2015
 * Time: 14:31
 */

class ServerAnswer {

    public $type;
    public $data;

    const SUCCESS = 'success';
    const ERROR = 'error';

    /**
     * @param $type
     * @param $data
     */
    function __construct($type, $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

}