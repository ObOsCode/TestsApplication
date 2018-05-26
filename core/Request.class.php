<?php

/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 06.10.2016
 * Time: 13:36
 */
class Request
{
    //Request methods
    const GET = "GET";
    const POST = "POST";

    private $_method;
    private $_paramsList = array();

    private $_serverName;
    private $_commandName;

    function __construct($method)
    {
        $this->_method = $method;

        switch($this->_method)
        {
            case self::GET:
                $this->_paramsList = $_GET;
                break;
            case self::POST:
                $this->_paramsList = $_POST;
                break;
        }

        $request_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        $params_string = str_replace(SERVER_PATH, '', $request_url);

        $params_list = explode('/', $params_string);

        if(!isset($params_list[0]))
        {
            $params_list[0]="";
        }

        if(!isset($params_list[1]))
        {
            $params_list[1]="";
        }

        $this->_serverName = $params_list[0];
        $this->_commandName = $params_list[1];
    }


    public function getServerName()
    {
        return $this->_serverName;
    }


    public function getCommandName()
    {
        return $this->_commandName;
    }


    public function getMethod()
    {
        return $this->_method;
    }


    public function getParam($paramName)
    {
        if(isset($this->_paramsList[$paramName]) && !empty($this->_paramsList[$paramName]))
        {
            return $this->_paramsList[$paramName];
        }else
        {
            return null;
        }
    }


    public function setParam($name, $value)
    {
        $this->_paramsList[$name] = $value;
    }

}