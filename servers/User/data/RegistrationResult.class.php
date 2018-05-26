<?php

/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 24.11.2015
 * Time: 14:19
 */
class RegistrationResult
{
    public $userId;
    public $message;

    function __construct($uid, $mes)
    {
        $this->userId = $uid;
        $this->message = $mes;
    }

}//class