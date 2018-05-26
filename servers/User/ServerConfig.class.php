<?php

/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 27.10.2015
 * Time: 13:47
 */
class ServerConfig extends ServerConfigBase
{
    //Если true Регистрировать/Авторизовывать по полю "login" если false, то по email
    public $authorizationByLogin = true;

    //Проверять ли валидность email при регистрации
    public $checkIsEmailValid = true;

    //Подтверждать ли регистрацию по почте
    public $confirmRegistrationByEmail = false;

    //Обязательны поля при регистрации
    public  $registration_required_fields = array("name", "soname", "password", "rePassword", "login", "capcha");

    //Список групп пользователей, создаваемых по умолчанию
    //(не могут быть удалены)
    public $defaultGroupsList = array("Notactivated", "Registered", "Administrator", "SuperAdministrator");

    //Группа пользователей по-умолчанию
    //(в нее будут добавляться новые зарегистрированные пользователи)
    public $defaultUserGroup = "Registered";

    //Группа пользователей по-умолчанию
    //(в нее будут добавляться  пользователи не прошедшие активацию)
    public $notActivatedUsersGroup = "Notactivated";

    //Группа для суперадминистратора
    public $superAdminGrgoup = "SuperAdministrator";

    //Папка в которой хранятся аватары
    public $avatarsFolderPath;

    //Имя файла аватара по умолчанию
    public $defaultAvatarFileName = "default.png";



    function __construct()
    {

        $this->avatarsFolderPath = SERVER_PATH.'upload/images/userAvatars/';

        //Можно задать свои настройки БД и прочей конфигурации
        //тем самым переписать дефолтные из ServerConfigBase
        //db
//        $this->db_host = "mysql.hostinger.ru";
//        $this->db_name = "u615523294_illbd";
//        $this->db_user = "u615523294_illus";
//        $this->db_password = "123StariyKon321";

    }

}