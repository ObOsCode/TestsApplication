<?php

/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 17.07.2015
 * Time: 14:58
 */
class ServerError
{
    public $code;
    public $message;

    /**
     * @param null $code
     * @param null $message
     */
    function __construct($message = null, $code = null)
    {
        $this->message = $message;
        $this->code = $code;

    }
}