<?php

/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 25.11.2015
 * Time: 13:13
 */
class UserGroup
{
    public $id;
    public $name;

    function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

}