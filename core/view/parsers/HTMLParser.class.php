<?php

/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 07.10.2016
 * Time: 10:42
 */
class HTMLParser implements IParser
{
    private $_request;

    private $_templatePath;

    function __construct(Request $request)
    {
        $this->_request = $request;

        $this->_templatePath = SERVER_ROOT."/templates/".TEMPLATE."/";
    }

    public function parse(ServerAnswer  $answer)
    {
        $file_path = $this->_templatePath.$this->_request->getServerName().".".$this->_request->getCommandName().".php";

        //answer or serverAnswer??????? param nah???
        global $serverAnswer, $request;

        if(file_exists($file_path))
        {
            include_once($file_path);
        }else
        {
            include_once($this->_templatePath."index.php");
        }
    }
}