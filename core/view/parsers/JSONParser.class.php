<?php
/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 20.01.2015
 * Time: 8:32
 */


class JSONParser implements IParser{

    function __construct()
    {

    }

    public function parse(ServerAnswer $answer)
    {
        return json_encode($answer);
    }

}