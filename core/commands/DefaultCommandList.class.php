<?php

require_once("Command.class.php");
require_once("CommandList.class.php");

/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 17.01.2017
 * Time: 16:18
 */
class DefaultCommandList extends CommandList
{

    function __construct()
    {
        //User server commands(Embeded comands)
        $loginCommand = new Command("User", "login", Command::ALL);
        $this->setDefaultCommand($loginCommand);

        $this->addCommand($loginCommand);
        $this->addCommand(new Command("User", "logout", Command::ALL));
        $this->addCommand(new Command("User", "registration", Command::ALL));
        $this->addCommand(new Command("User", "removeUser", Command::ADMIN_ONLY));
        $this->addCommand(new Command("User", "editUser", Command::AUTHORIZED_ONLY));
        $this->addCommand(new Command("User", "activation", Command::ALL));

        $this->addCommand(new Command("User", "addGroup", Command::ADMIN_ONLY));
        $this->addCommand(new Command("User", "removeGroup", Command::ADMIN_ONLY));
        $this->addCommand(new Command("User", "editGroup", Command::ADMIN_ONLY));
        $this->addCommand(new Command("User", "addUserToGroup", Command::ADMIN_ONLY));
        $this->addCommand(new Command("User", "removeUserFromGroup", Command::ADMIN_ONLY));

        $this->addCommand(new Command("User", "getUserList", Command::ADMIN_ONLY));

        //File server commands(Embeded comands)
        $this->addCommand(new Command("File", "upload", Command::ADMIN_ONLY));
        $this->addCommand(new Command("File", "remove", Command::ADMIN_ONLY));
    }

}