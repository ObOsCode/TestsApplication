<?php

/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 07.10.2016
 * Time: 10:58
 */
class XMLParser implements IParser
{

    public function parse(ServerAnswer $answer)
    {
        // TODO: Implement init() method.
        echo "Hello from XML parser!!!!!\n";
        print_r($answer);
    }
}