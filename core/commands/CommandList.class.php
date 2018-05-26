<?php

require_once("Command.class.php");

/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 17.01.2017
 * Time: 16:08
 */
class CommandList
{

    private $_commandsList = array();
    private $_defailtCommand;

    function __construct()
    {

    }


    public function setDefaultCommand(Command $command)
    {
        $this->_defailtCommand = $command;
    }


    public function getDefaultCommand()
    {
        return $this->_defailtCommand;
    }


    public function addCommand(Command $command)
    {
        array_push($this->_commandsList, $command);
    }


    public function getCommand($serverName, $commandName)
    {
        $command = null;

        foreach($this->_commandsList as $com)
        {

            if($com->getCommandName() == $commandName && $com->getServerName() == $serverName)
            {

                $command = $com;
                break;
            }
        }

        return $command;
    }

}