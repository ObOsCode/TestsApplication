<?php

//require_once("SessionHandler.php");



/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 13.10.2016
 * Time: 11:27
 */

class SessionManager
{
    protected $savePath;
    protected $sessionName;

    public function __construct() {
//        session_set_save_handler(
//            array($this, 'open'),
//            array($this, 'close'),
//            array($this, 'read'),
//            array($this, 'write'),
//            array($this, 'destroy'),
//            array($this, 'gc')
//        );

    }

    public function open($savePath, $sessionName) {
        $this->savePath = $savePath;
        $this->sessionName = $sessionName;
//        /echo 'Сессия открыта<br />';
//        echo 'Путь сессии: '.$savePath.'<br />';
//        echo 'Имя сессии: '.$sessionName.'<br />';
        //return parent::open($savePath, $sessionName);
//        return true;
    }

    public function close() {
        //echo 'Сессия закрыта<br />';
        return true;
    }

    public function read($id) {
//        echo 'Сессия прочитана<br />';
//        echo 'ID сессии: '.$id.'<br />';
        return "";
    }

    public function write($id, $data) {
//        echo 'Сессия записана<br />';
//        echo 'ID сессии: '.$id.'<br />';
//        echo 'Данные: '.$data.'<br />';
        return true;
    }

    public function destroy($id) {
//        echo 'Сессия id='.$id.' уничтожена<br />';
        return true;
    }

    public function gc($maxlifetime) {
//        echo 'Запущен сборщик мусора<br />';
//        echo 'Время жизни сессии: '.$maxlifetime.'<br />';
        return true;
    }
}