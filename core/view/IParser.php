<?php

/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 21.07.2015
 * Time: 15:33
 */


interface IParser
{

    public function parse(ServerAnswer $answer);

}