<?php

/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 06.08.2015
 * Time: 15:13
 */
class User
{
    public $isAuth = false;
    public $isAdmin = false;

    public $id;
    public $login;
    public $email;
    public $name;
    public $soname;
    public $avatarURL;
    public $registrationDate;
    public $sessionId;
    public $groupsList = array();


}