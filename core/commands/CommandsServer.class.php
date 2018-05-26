<?php

/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 21.07.2015
 * Time: 14:57
 */

require_once("Command.class.php");
require_once(SERVER_ROOT."/core/ServerError.class.php");
require_once(SERVER_ROOT."/core/ServerAnswer.class.php");

class CommandsServer
{

    private $_commandsList;

    private $_request;

    private $_result;

    public static $user;

    function __construct(Request $request, DefaultCommandList $commandList)
    {
        $this->_request = $request;
        $this->_commandsList = $commandList;
    }

    //////////////////
    //Public
    //////////////////

    public function run()
    {
        $command = $this->_commandsList->getCommand($this->_request->getServerName(), $this->_request->getCommandName());

        if(!$command)
        {
            //Если формат вывода Template рессылимся на команду по умолчанию, если нет - выводим
            //ошибку "Команда не найдена"
            if(DEFAULT_ANSWER_FORMAT == ANSWER_FORMAT_TEMPLATE)
            {
                $newCommand = $this->_commandsList->getDefaultCommand();

                $location = SERVER_PATH.$newCommand->getServerName()."/".$newCommand->getCommandName();

                header("location:".$location);
            }else
            {
                $this->setError(new ServerError("Команда не найдена в списке", 301));
                return;
            }
        }

        //Проверка прав доступа на выполнение команды
        //..

        require_once(SERVER_ROOT."/servers/User/UserServer.class.php");

        //Лучше перенести проверку авторизации в отдельный класс, а в
        //UserServer оставить только команды

        $userServer = new UserServer();

        $userServer->setRequest($this->_request);

        $userServer->checkSession();

        self::$user = $userServer->getUser();

        //Если команда доступна только авторизованным пользователям
        if($command->getAvailable() == Command::AUTHORIZED_ONLY)
        {
            require_once(SERVER_ROOT."/servers/User/UserServer.class.php");

            if(!self::$user->isAuth)
            {
                //Если формат вывода Template рессылимся на страницу авторизации, если нет выводим ошибку
                if(DEFAULT_ANSWER_FORMAT == ANSWER_FORMAT_TEMPLATE)
                {
                    header("location:".SERVER_PATH."User/login/");
                }else
                {
                    $this->setError(new ServerError("Команда доступна только авторизованым пользователям", 300));
                    return;
                }
            }

            //Если команда доступна только администраторам
        }elseif($command->getAvailable() == Command::ADMIN_ONLY)
        {
            require_once(SERVER_ROOT."/servers/User/UserServer.class.php");

            if(!self::$user->isAdmin)
            {
                $this->setError(new ServerError("Команда доступна только администраторам", 300));
                return;
            }
        }

        $server_name = $command->getServerName();

        $filePath = SERVER_ROOT.'/servers/'.$server_name.'/'.$server_name.'Server.class.php';

        require_once($filePath);

        $server_class_name = $server_name.'Server';

        $server = new $server_class_name();

        $server->setRequest($this->_request);

        $command_name = $command->getCommandName();

        if(!method_exists($server, $command_name))
        {
            $this->setError(new ServerError("Сервер ".$server_class_name." не поддерживает команду - " .$command_name));
            return;
        }

        $success_result = $server->$command_name();

        //Проверяем были ли ошибки
        if($error = $server->getError())
        {
            $this->_result = new ServerAnswer(ServerAnswer::ERROR, $error);
        }else
        {
            //Если не было ошибок
            $this->_result = new ServerAnswer(ServerAnswer::SUCCESS, $success_result);
        }
    }

    public function getResult()
    {
        return $this->_result;
    }


    ///////////////////
    //Private
    //////////////////


    private function setError(ServerError $error)
    {
        $this->_result = new ServerAnswer(ServerAnswer::ERROR, $error);
    }

}