<?php

/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 29.10.2015
 * Time: 9:02
 */
class Utils
{
    //Check email valid
    public static function isEmailValid($email)
    {
        return preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $email);
    }

    //Send mail
    public static function sendMail($to,$subject, $message)
    {
        mail($to, $subject, $message);
    }
}

