<?php
/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 02.07.2015
 * Time: 15:28
 */


class Command {

    const AUTHORIZED_ONLY = 1;
    const ADMIN_ONLY = 2;
    const ALL = 3;

    private $_serverName;
    private $_commandName;
    private $_available;


    /**
     * @param $name
     * @param $server_class
     * @param $available
     */
    function __construct($serverName, $commandName, $available)
    {
        $this->_serverName = $serverName;
        $this->_commandName = $commandName;
        $this->_available = $available;
    }

    public function getServerName()
    {
        return $this->_serverName;
    }

    public function getCommandName()
    {
        return $this->_commandName;
    }

    public function getAvailable()
    {
        return $this->_available;
    }


}//class