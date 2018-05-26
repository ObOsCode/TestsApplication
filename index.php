<?php
require_once("config.php");

//require_once(SERVER_ROOT."/core/SessionManager.class.php");
//$handler = new SessionManager();

session_start();

//echo session_id();

require_once(SERVER_ROOT . "/core/commands/DefaultCommandList.class.php");
require_once(SERVER_ROOT."/core/commands/CommandsServer.class.php");
require_once(SERVER_ROOT."/core/Request.class.php");
require_once(SERVER_ROOT."/core/view/Viewer.class.php");

$request = new Request($_SERVER["REQUEST_METHOD"]);

//DefaultCommandList - список команд содержащий уже созданные команды по умолчанию
$commandList = new DefaultCommandList();

////Custom commands
$testListCommand = new Command("Tests", "getList", Command::AUTHORIZED_ONLY);
$commandList->addCommand($testListCommand);
$commandList->addCommand(new Command("Tests", "getTest", Command::AUTHORIZED_ONLY));
$commandList->addCommand(new Command("Tests", "getQuestion", Command::AUTHORIZED_ONLY));
$commandList->addCommand(new Command("Tests", "checkAnswer", Command::AUTHORIZED_ONLY));
$commandList->addCommand(new Command("Tests", "addResult", Command::AUTHORIZED_ONLY));
$commandList->addCommand(new Command("Tests", "getHint", Command::AUTHORIZED_ONLY));
$commandList->addCommand(new Command("Tests", "getResults", Command::AUTHORIZED_ONLY));

$commandList->addCommand(new Command("Admin", "testsList", Command::ADMIN_ONLY));
$commandList->addCommand(new Command("Admin", "editTest", Command::ADMIN_ONLY));
$commandList->addCommand(new Command("Admin", "getStatistic", Command::ADMIN_ONLY));


//.........

//Set custom default command
$commandList->setDefaultCommand($testListCommand);

$commandsServer = new CommandsServer($request, $commandList);

$commandsServer->run();

$serverAnswer = $commandsServer->getResult();

$viewer = new Viewer($request);

$viewer->showAnswer($serverAnswer);
