
<?php

//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('error_reporting',  E_ALL);

//DB
define("DEFAULT_DB_NAME", "tests_app");
define("DEFAULT_DB_HOST", "localhost");
define("DEFAULT_DB_USER", "root");
define("DEFAULT_DB_PASS", "iddqdidkfa");

//Auth Session storage type

//Сессии хранятся в базе данных
//id передается при каждом запросе от приложения
define("DB_SESSION_FOR_APP", "databaseSessionsForApplications");

//Стандартные сессии сервера
// server php default
define("SERVER_SESSION", "serverSession");

//Вид системы авторизации и аутентификации
define("AUTH_TYPE", SERVER_SESSION);
//define("AUTH_TYPE", DB_SESSION_FOR_APP);


//Answer formats
define("ANSWER_FORMAT_JSON", "json");
define("ANSWER_FORMAT_XML", "xml");//Парсер еще не написан
define("ANSWER_FORMAT_TEMPLATE", "template");//Парсер еще не написан

//define("DEFAULT_ANSWER_FORMAT", ANSWER_FORMAT_JSON);
define("DEFAULT_ANSWER_FORMAT", ANSWER_FORMAT_TEMPLATE);
define("TEMPLATE", "default");

//Charset
define("CHARSET",'utf-8');

//Server path
define('SERVER_PATH', pathinfo("http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'], PATHINFO_DIRNAME) . '/');

//Server root
define("SERVER_ROOT", dirname(__FILE__));

//Debug mode
define('DEBUG', false);
