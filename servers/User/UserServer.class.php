<?php

/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 21.07.2015
 * Time: 16:06
 */

require_once(SERVER_ROOT."/core/Server.class.php");
require_once("data/User.class.php");


class UserServer extends Server
{

    const USERS_TABLE_NAME = "users";
    const GROUPS_TABLE_NAME = "usergroups";
    const USER_HAS_GROUP_TABLE_NAME = "user_has_group";
    const USER_SESSIONS_TABLE_NAME = "user_sessions";

    //Время жизни сессии в секундах (14 мин)
    const SESSION_LIFE_TIME = 14 * 60;

    private $_user;


    function __construct()
    {
        parent::__construct();

        $this->_user = new User();
    }


    //Переименовать в checkAuthorization
    public function checkSession()
    {
        $isSession = false;
        $user_id = null;
        $session_id = null;

        if(AUTH_TYPE == SERVER_SESSION)
        {
            $isSession = isset($_SESSION['session_id']) && ($_COOKIE["session"] == $_SESSION['session_id']);

            if($isSession)
            {
                $user_id = $_SESSION["user_id"];
                $session_id = $_SESSION['session_id'];
            }

        }
        elseif (AUTH_TYPE == DB_SESSION_FOR_APP)
        {
            if($this->isRequestParamsSet(array("session")))
            {
                $session_from_params = $this->getRequestParam("session");

                $session_obj = null;

                try
                {
                    $session_obj = $this->_db->getRow("SELECT * FROM ".self::USER_SESSIONS_TABLE_NAME." WHERE session_id=?s", $session_from_params);
                }
                catch (Exception $ex)
                {
                    $this->setError(new ServerError($ex->getMessage()));
                }

                if($session_obj)
                {
                    $last_date = $session_obj["last_date"];

                    //14 minutes session life
                    if((time() - strtotime($last_date)) < (14*60))
                    {
                        $isSession = true;

                        $user_id = $session_obj["user_id"];
                        $session_id = $session_obj["session_id"];

                        $this->editTableRow(self::USER_SESSIONS_TABLE_NAME, $session_obj["id"], array("last_date"=>null));
                    }
                    else
                    {
                        $this->destroySession($session_obj["user_id"]);
                    }
                }
            }else
            {
//                Надо вывести предупреждение "Не передана сессия"
//                echo "Не передана сессия!\n";
            }
        }

        if($isSession && $user_id && $session_id)
        {
            $this->_user = $this->getUserById($user_id);
            $this->_user->isAuth = true;
            $this->_user->sessionId = $session_id;

        }
//                print_r($this->_user);
    }



    /**
     * @return User
     */
    public function getUser()
    {
        return $this->_user;
    }


//    public static function userId()
//    {
//        return $_SESSION['user_id'];
//    }



    public function logout()
    {
//        $this->destroySession(CommandsServer::$user->id);
        if($this->destroySession())
        {
            return "Вы вышли.";
        }else
        {
            return "Выйти не удалось.";
        }
    }


    /**
     * @return User
     */
    public function login()
    {

        if(CommandsServer::$user->isAuth)
        {
            $this->setError(new ServerError("Вы уже авторизованы на сервере ".$_SERVER['HTTP_HOST']));
            return null;
        }

        //Определяем поле по которому будем проходить авторизацию
        // и регистрацию
        $authorization_field_name = $this->getAuthorizationFieldName();

        //Проверяем обязательные параметры
        if(!$this->isRequestParamsSet(array("password", $authorization_field_name)))
        {
            $this->setError(new ServerError("Ошибка авторизации. Переданы не все данные."));
            return null;
        }

        $authorization_field = $this->getRequestParam($authorization_field_name);
        $password = $this->getRequestParam("password");

        try
        {
            $result = $this->_db->getRow("SELECT * FROM ".self::USERS_TABLE_NAME." WHERE ".$authorization_field_name."=?s", $authorization_field);
        }
        catch (Exception $ex)
        {
            $this->setError(new ServerError($ex->getMessage()));
        }

        if(!$result)
        {
            $this->setError(new ServerError("Ошибка авторизации. Не правильный логин или пароль."));
            return null;
        }

        $real_password = $result['password'];

        if(md5($password)==$real_password)
        {
            if(!$result['status'])
            {
                $this->setError(new ServerError("Ваш аккаунт не был подтвержден. Проверьте электропочту."));
                return null;
            }

            //Разлогиниваемся из предыдущего аккаунта что бы не допустить одновременной
            //авторизации нескольких пользователей(т.к. при этом глюки с правами)
//                $this->logout();

            $user_id = $result['id'];

            $user = new User();
            $user->id = $result['id'];
            $user->login = $result['login'];
            $user->email = $result['email'];
            $user->name = $result['name'];
            $user->soname = $result['soname'];
            $user->avatarURL = $this->_config->avatarsFolderPath.$result['avatar'];
            $user->registrationDate = $result['registration_date'];

            //Определяем группы в которых состоит пользователь
            $sql = 'SELECT * FROM '.self::GROUPS_TABLE_NAME.' WHERE id IN
            (SELECT group_id FROM '.self::USER_HAS_GROUP_TABLE_NAME.' WHERE user_id=?i)';

            $groups_list = $this->_db->getAll($sql, $user_id);

            require_once("data/UserGroup.class.php");

            //Является ли пользователь админом
            $is_admin = false;

            foreach($groups_list as $group)
            {
                $group_id = $group['id'];
                $group_name = $group['name'];

                if($group_name=="Administrator"||$group_name=="SuperAdministrator")
                {
                    $is_admin  = true;
                }

                $user_group = new UserGroup($group_id, $group_name);

                array_push($user->groupsList, $user_group);
            }

            $session_id = $this->setSession($user_id, $user->isAdmin);

            $user->isAuth = true;
            $user->isAdmin = $is_admin;
            $user->sessionId = $session_id;

            return $user;
        }
        else
        {
            $this->setError(new ServerError("Ошибка авторизации. Не правильный логин или пароль."));
            return null;
        }
    }


    ///////////////
    //Registration
    ///////////////
    public function registration()
    {
        /*
         *Определяем поле по которому будем проходить авторизацию и регистрацию(по логину или email)
        */
        $authorization_field_name = $this->getAuthorizationFieldName();

        if(
        //Если не переданы обязательные параметры
//        !$this->isRequestParamsSet(array("password", "rePassword", $authorization_field_name))||
        !$this->isRequestParamsSet($this->_config->registration_required_fields)||
        //Или если требуется подтверждение регистрации по email, а он не передан
        ($this->_config->confirmRegistrationByEmail && !$this->isRequestParamsSet(array("email")))
        )
        {
            //То возвращаем ошибку
            $this->setError(new ServerError("Ошибка регистрации. Переданы не все данные."));
            return null;
        }

        $authorization_field = $this->getRequestParam($authorization_field_name);

        /*
         * Если передан email проверяем его на валидность
         * если это установлено в настройках
         *(Не зависимо от того передан он для авторизации, подтверждения регистрации или
         * как необязательный параметр)
         */
        if($this->isRequestParamsSet(array("email")) && $this->_config->checkIsEmailValid)
        {
            require_once(SERVER_ROOT."/core/Utils.class.php");
            if(!Utils::isEmailValid($this->getRequestParam("email")))
            {
                $this->setError(new ServerError("Ошибка регистрации. Некоректный e-mail адрес."));
                return null;
            }
        }

        //Если в конфигурации сервера прописано подтверждение регистрации
        //по email вычисляем код активации и выставляем статус
        //активации в 0
        $activation_code = null;
        $activation_status = 1;

        if($this->_config->confirmRegistrationByEmail)
        {
            $activation_code = md5($authorization_field.time());
            $activation_status = 0;
        }

        $user_password = $this->getRequestParam("password");
        $user_re_password = $this->getRequestParam("rePassword");

        if($user_password !== $user_re_password)
        {
            $this->setError(new ServerError("Ошибка регистрации. Пароли не совпадают."));
            return null;
        }

        if(!($_SESSION['randomnr2'] == md5($this->getRequestParam("capcha"))))
        {
            $this->setError(new ServerError("Не верно введен код с картинки"));
            return null;
        }

        if($this->isRowExist($this::USERS_TABLE_NAME, $authorization_field, $authorization_field_name))
        {
            $this->setError(new ServerError("Ошибка регистрации. Пользователь с ".$authorization_field_name." ".$authorization_field." уже зарегистрирован."));
            return null;
        }

        $user_fields = array(
            'id'=>null,
            'email'=>$this->getRequestParam("email"),
            'login'=>$this->getRequestParam("login"),
            'name'=>$this->getRequestParam("name"),
            'soname'=>$this->getRequestParam("soname"),
            'password'=>md5($user_password),
            'activation_code'=>$activation_code,
            'status'=>$activation_status
        );

        if($this->getRequestParam("avatar"))
        {
            $user_fields["avatar"] = $this->getRequestParam("avatar");
        }

        //Добавляем пользователя в таблицу
        try
        {
            $this->_db->query('INSERT INTO '.self::USERS_TABLE_NAME.' SET ?u', $user_fields);
        }
        catch (Exception $ex)
        {
            $this->setError(new ServerError($ex->getMessage()));
        }

        $new_user_id = $this->_db->insertId();

        $new_user_group_id = null;

        //Если в конфигурации сервера прописано подтверждение регистрации по email
        //то заносим пользователя в группу не активированных пользователей и
        // отправляем пользователю письмо с ссылкой на активацию
        if($this->_config->confirmRegistrationByEmail)
        {
            $this->sendConfirmMailToUser($this->getRequestParam("email"), $activation_code);
            $result_message = "На Ваш почтовый ящик отправлено письмо.
                Перейдите по ссылке указанной в письме
                для подтверждения регистрации.";
            //Определяем Id группы не активированных пользователей
            try
            {
                $new_user_group_id = $this->_db->getOne('SELECT id FROM '.self::GROUPS_TABLE_NAME.' WHERE name=?s', $this->_config->notActivatedUsersGroup);
            }
            catch (Exception $ex)
            {
                $this->setError(new ServerError($ex->getMessage()));
            }

        }else
        {
            //Определяем Id группы по умолчанию
            try
            {
                $new_user_group_id = $this->_db->getOne('SELECT id FROM '.self::GROUPS_TABLE_NAME.' WHERE name=?s', $this->_config->defaultUserGroup);
            }
            catch (Exception $ex)
            {
                $this->setError(new ServerError($ex->getMessage()));
            }

            $result_message = "Вы успешно зарегистрировались.";
        }

        //Добавляем пользователя в группу определенную в зависимости от настроек активации
        $this->setRequestParam("groupId", $new_user_group_id);
        $this->setRequestParam("userId", $new_user_id);
        $this->addUserToGroup();

        require_once("data/RegistrationResult.class.php");

        return new RegistrationResult($new_user_id, $result_message);
    }



    ///////////////
    //Remove user
    ///////////////

    public function removeUser()
    {
        if(!$this->isRequestParamsSet(array("id")))
        {
            $this->setError(new ServerError("Не переданы параметры"));
            return null;
        }

        $user_id = $this->getRequestParam("id");

        //Проверка существования пользователя
        //Надо заменить на isRoeExist
        $user = $this->_db->getRow("SELECT * FROM ".self::USERS_TABLE_NAME." WHERE id=?i", $user_id);

        if(!$user)
        {
            $this->setError(new ServerError("Не удалось удалить пользователя. Пользователь с id - ".$user_id." не существует."));
            return null;
        }

        //Удаление аватара

        $user_avatar_name = $user["avatar"];

        //Удаляем если у пользователя не дефолтный аватар
        if($user_avatar_name!=$this->_config->defaultAvatarFileName)
        {
            require_once(SERVER_ROOT."/servers/File/FileServer.class.php");

            $file_server = new FileServer();
            $file_server->setRequestParam("folder", quotemeta("images/userAvatars/"));
            $file_server->setRequestParam("fileName", $user_avatar_name);
            $file_server->setViewer($this->_viewer);
            $file_server->remove();
        }

        //Удаление информации о группах пользователя
        try
        {
            $this->_db->query("DELETE FROM ".self::USER_HAS_GROUP_TABLE_NAME." WHERE user_id=?i", $user_id);
        }
        catch (Exception $ex)
        {
            $this->setError(new ServerError("Не удалось удалить информацию о группах пользователя. \n".$ex->getMessage()));
        }

        //Удаление пользователя
        try
        {
            $this->_db->query("DELETE FROM ".self::USERS_TABLE_NAME." WHERE id=?i", $user_id);
        }
        catch (Exception $ex)
        {
            $this->setError(new ServerError($ex->getMessage()));
        }


        return "Пользователь удален.";

    }

    public function editUser()
    {
        require_once(SERVER_ROOT."/core/Utils.class.php");

        if(!$this->isRequestParamsSet(array("id")))
        {
            $this->setError(new ServerError("Не передан id пользователя."));
            return null;
        }

        $id = $this->getRequestParam("id");

        if(!$this->isRowExist($this::USERS_TABLE_NAME, $id))
        {
            $this->setError(new ServerError("Пользователь с id - ".$id." не существует."));
            return null;
        }

        $user_fields = array();

        if($this->isRequestParamsSet(array("email"))) $user_fields['email'] = $this->getRequestParam("email");
        if($this->isRequestParamsSet(array("login"))) $user_fields['login'] = $this->getRequestParam("login");
        if($this->isRequestParamsSet(array("password"))) $user_fields['password'] = md5($this->getRequestParam("password"));
        if($this->isRequestParamsSet(array("name"))) $user_fields['name'] = $this->getRequestParam("name");
        if($this->isRequestParamsSet(array("soname"))) $user_fields['soname'] = $this->getRequestParam("soname");
        if($this->isRequestParamsSet(array("avatar"))) $user_fields['avatar'] = $this->getRequestParam("avatar");

        //Проверяем почтовый адрес на валидность
        //если он передан и если это установлено в настройках
        if($this->_config->checkIsEmailValid && isset($user_fields['email']) && !Utils::isEmailValid($user_fields['email']))
        {
            $this->setError(new ServerError("Некоректный e-mail адрес."));
            return null;
        }

        $this->editTableRow($this::USERS_TABLE_NAME, $id, $user_fields);

        return "Информация пользователя изменена.";
    }

    ///////////////
    //Activation
    ///////////////

    public function activation()
    {
        //При активации параметр activation_code всегда передается через $_GET в строке запроса
        //Активация происходит через браузер
        if(!$_GET["activation_code"])
        {
            $this->setError(new ServerError("Не передан код активации"));
            return null;
        }else
        {
            $activation_code = $_GET["activation_code"];
        }

        $user_id = $this->_db->getOne("SELECT id FROM ".self::USERS_TABLE_NAME." WHERE activation_code=?s", $activation_code);

        if($user_id)
        {
            try
            {
                $this->_db->query("UPDATE ".self::USERS_TABLE_NAME." SET ?u  WHERE activation_code=?s", array("activation_code"=>null,"status"=>"1"), $activation_code);
            }
            catch (Exception $ex)
            {
                $this->setError(new ServerError($ex->getMessage()));
            }
        }else
        {
            $this->setError(new ServerError("Неверная ссылка"));
            return null;
        }


        //Удаляем пользователя из группы не активированных
        // и добавляем в группу по умолчанию


        $group_id = null;

        $this->setRequestParam("userId", $user_id);

        //Определяем Id группы не активированных пользователей
        try
        {
            $group_id = $this->_db->getOne('SELECT id FROM '.self::GROUPS_TABLE_NAME.' WHERE name=?s', $this->_config->notActivatedUsersGroup);
        }
        catch (Exception $ex)
        {
            $this->setError(new ServerError($ex->getMessage()));
        }

        //Удаляем из группы не активированных
        $this->setRequestParam("groupId", $group_id);
        $this->removeUserFromGroup();

        //Определяем Id группы по умолчанию
        try
        {
            $group_id = $this->_db->getOne('SELECT id FROM '.self::GROUPS_TABLE_NAME.' WHERE name=?s', $this->_config->defaultUserGroup);
        }
        catch (Exception $ex)
        {
            $this->setError(new ServerError($ex->getMessage()));
        }

        //Добавляем в группу по умолчанию
        $this->setRequestParam("groupId", $group_id);
        $this->addUserToGroup();

        return "Ваш аккаунт активирован";
    }


    //Добавить группу
    public function addGroup()
    {
        if(!$this->isRequestParamsSet(array("name")))
        {
            $this->setError(new ServerError("Не передано имя группы"));
            return null;
        }

        $group_name = $this->getRequestParam("name");

        //Проверяем наличие группы с таким именем

        if($this->isRowExist($this::GROUPS_TABLE_NAME, $group_name, "name"))
        {
            $this->setError(new ServerError("Группа с именем " . $group_name . " уже существует."));
            return null;
        }

        //Добавляем группу в таблицу БД
        $group_fields = array('id'=>null, 'name'=>$group_name);
        try
        {
            $this->_db->query('INSERT INTO '.self::GROUPS_TABLE_NAME.' SET ?u', $group_fields);
        }
        catch (Exception $ex)
        {
            $this->setError(new ServerError($ex->getMessage()));
        }

        require_once("data/UserGroup.class.php");

        return new UserGroup($this->_db->insertId(), $group_name);
    }


    public function editGroup()
    {
        if(!$this->isRequestParamsSet(array("id")))
        {
            $this->setError(new ServerError("Не передан id группы."));
            return null;
        }

        $id = $this->getRequestParam("id");


        //Проверяем не является ли удаляемая группа группой по умолчанию.
        $changedGroupName = $this->_db->getOne("SELECT name FROM ".self::GROUPS_TABLE_NAME." WHERE id=?i", $id);

        if($this->groupIsDefault($changedGroupName))
        {
            $this->setError(new ServerError("Невозможно изменить группу. \n".$changedGroupName." - является группой по умолчанию."));
            return null;
        }

        if(!$this->isRowExist($this::GROUPS_TABLE_NAME, $id))
        {
            $this->setError(new ServerError("Группа с id ".$id." не существует."));
            return null;
        }

        $group_fields = array();

        if($this->isRequestParamsSet(array("name"))) $group_fields['name'] = $this->getRequestParam("name");

        $this->editTableRow($this::GROUPS_TABLE_NAME, $id, $group_fields);

        return "Информация группы изменена.";
    }


    //Удалить группу
    public function removeGroup()
    {
        if(!$this->isRequestParamsSet(array("id")))
        {
            $this->setError(new ServerError("Не передан id группы"));
            return null;
        }

        $group_id = $this->getRequestParam("id");


        //Проверяем не является ли удаляемая группа группой по умолчанию.

        $deletedGroupName = $this->_db->getOne("SELECT name FROM ".self::GROUPS_TABLE_NAME." WHERE id=?i", $group_id);

        if($this->groupIsDefault($deletedGroupName))
        {
            $this->setError(new ServerError("Невозможно удалить группу. \n".$deletedGroupName." - является группой по умолчанию."));
            return null;
        }

        //Удаление группы
        try
        {
            $this->_db->query("DELETE FROM ".self::GROUPS_TABLE_NAME." WHERE id=?i", $group_id);
        }
        catch (Exception $ex)
        {
            $this->setError(new ServerError($ex->getMessage()));
        }

        if($this->_db->affectedRows()!=1)
        {
            $this->setError(new ServerError("Не удалось удалить группу. Группа с id - ".$group_id." не существует."));
            return null;
        }

        //Удаление информации о пренадлежности пользователей к этой группе
        try
        {
            $this->_db->query("DELETE FROM ".self::USER_HAS_GROUP_TABLE_NAME." WHERE group_id=?i", $group_id);
        }
        catch (Exception $ex)
        {
            $this->setError(new ServerError("Не удалось удалить информацию о группе".$ex->getMessage()));
        }

        return "Группа удалена.";
    }


    //Поместить пользователя в группу
    public function addUserToGroup()
    {
        if(!$this->isRequestParamsSet(array("groupId", "userId")))
        {
            $this->setError(new ServerError("Ошибка добавления пользователя в группу. Переданы не все данные."));
            return null;
        }

        $group_id = $this->getRequestParam("groupId");
        $user_id = $this->getRequestParam("userId");

        //Проверяем существуют ли пользователь и группа с переданными id
        if(!$this->isRowExist($this::USERS_TABLE_NAME, $user_id))
        {
            $this->setError(new ServerError("Пользователь с id - ".$user_id." не существует"));
            return null;
        }

        if(!$this->isRowExist($this::GROUPS_TABLE_NAME, $group_id))
        {
            $this->setError(new ServerError("Группа с id - ".$group_id." не существует"));
            return null;
        }

        //Проверяем не находится ли уже пользователь в этой группе
        try
        {
            $results = $this->_db->getAll("SELECT * FROM " . self::USER_HAS_GROUP_TABLE_NAME . " WHERE user_id=?i AND group_id=?i", $user_id, $group_id);
        }
        catch (Exception $ex)
        {
            $this->setError(new ServerError($ex->getMessage()));
            return null;
        }

        if(count($results)>0)
        {
            $this->setError(new ServerError("Пользователь уже состоит в данной группе"));
            return null;
        }

        //Добавляем пользователя в группу
        try
        {
            $this->_db->query('INSERT INTO '.self::USER_HAS_GROUP_TABLE_NAME.' SET ?u', array("user_id"=>$user_id, "group_id"=>$group_id));
        }
        catch (Exception $ex)
        {
            $this->setError(new ServerError($ex->getMessage()));
        }

        return "Пользователь добавлен в группу.";
    }

    //Удалить пользователя из группы
    public function removeUserFromGroup()
    {
        if (!$this->isRequestParamsSet(array("groupId", "userId"))) {
            $this->setError(new ServerError("Переданы не все данные."));
            return null;
        }

        $group_id = $this->getRequestParam("groupId");
        $user_id = $this->getRequestParam("userId");

        //Проверяем существуют ли пользователь и группа с переданными id
        //(при удалении проверка не обязателна, те. целостность данных не нарушится).
        //Сделано для удобства
        if(!$this->isRowExist($this::USERS_TABLE_NAME, $user_id))
        {
            $this->setError(new ServerError("Пользователь с id - ".$user_id." не существует"));
            return null;
        }

        if(!$this->isRowExist($this::GROUPS_TABLE_NAME, $group_id))
        {
            $this->setError(new ServerError("Группа с id - ".$group_id." не существует"));
            return null;
        }

        //Проверяем яыляется ли пользователь членом группы

        $results = $this->_db->getAll("SELECT * FROM " . self::USER_HAS_GROUP_TABLE_NAME . " WHERE user_id=?i AND group_id=?i", $user_id, $group_id);

        if (count($results) == 0)
        {
            $this->setError(new ServerError("Пользователь c id - ".$user_id." не является членом группы с id - ". $group_id));
            return null;
        }

        //Удаляем пользователя из группы
        try
        {
            $this->_db->query("DELETE FROM ".self::USER_HAS_GROUP_TABLE_NAME." WHERE user_id=?i AND group_id=?i", $user_id, $group_id);
        }
        catch (Exception $ex)
        {
            $this->setError(new ServerError("Ошибка удаления пользователя. ".$ex->getMessage()));
        }

        return "Пользователь удален из группы.";
    }


    //Список пользователей
    public function getUserList()
    {
        $filter_fields = array();

        if($this->isRequestParamsSet(array("id"))) $filter_fields['id'] = $this->getRequestParam("id");
        if($this->isRequestParamsSet(array("email"))) $filter_fields['email'] = $this->getRequestParam("email");
        if($this->isRequestParamsSet(array("login"))) $filter_fields['login'] = $this->getRequestParam("login");
        if($this->isRequestParamsSet(array("name"))) $filter_fields['name'] = $this->getRequestParam("name");
        if($this->isRequestParamsSet(array("avatar"))) $filter_fields['avatar'] = $this->getRequestParam("avatar");
//        if($this->isRequestParamsSet(ar   ray("status"))) $filter_fields['status'] = $this->getRequestParam("status");

        //!!!!!!!!!!!!isRequestParamsSet не возвращает status если он равен 0
        //empty(status=0) Не проходит проверку

        //Хорошо бы добавить фильтр по группе

        return $this->selectAllFromTableWithFilter(self::USERS_TABLE_NAME, $filter_fields);
    }


    ///////////////
    //Private
    ///////////////

    //Проверяет является ли группа с передаваемым именем группой по умолчанию
    //создаваемой при установке сервера.
    //Список имен этих групп задается в конфигурации UserServer'а
    private function groupIsDefault($group_name)
    {
        foreach($this->_config->defaultGroupsList as $group)
        {
            if($group==$group_name)
            {
                return true;
            }
        }
        return false;
    }

    //Возвращает поле по которому осуществляется авторизация login или email
    private function getAuthorizationFieldName()
    {
        if(isset($this->_config->authorizationByLogin) && $this->_config->authorizationByLogin)
        {
            return "login";
        }else
        {
            return "email";
        }
    }

    //Отправляет письмо пользователю с подтверждением регистрации
    private function sendConfirmMailToUser($user_email, $activation_code)
    {
        require_once(SERVER_ROOT."/core/Utils.class.php");
        $subject = "Подтверждение регистрации на сайте ".SERVER_PATH;
        $message = "Активируйте свой аккаунт перейдя по ссылке ".SERVER_PATH."User/activation/?activation_code=".$activation_code;
        Utils::sendMail($user_email, $subject, $message);
    }


    private function getUserById($id)
    {
        $result = null;
        try
        {
            $result = $this->_db->getRow("SELECT * FROM ".self::USERS_TABLE_NAME." WHERE id=?i", $id);
        }
        catch (Exception $ex)
        {
            $this->setError(new ServerError($ex->getMessage()));
        }

        if(!$result)
        {
            $this->setError(new ServerError("Пользователь с id ".$id." не существует."));
            return null;
        }

        $user = new User();
        $user->id = $result['id'];
        $user->login = $result['login'];
        $user->email = $result['email'];
        $user->name = $result['name'];
        $user->soname = $result['soname'];
        $user->avatarURL = $this->_config->avatarsFolderPath.$result['avatar'];
        $user->registrationDate = $result['registration_date'];

        //Определяем группы в которых состоит пользователь
        $sql = 'SELECT * FROM '.self::GROUPS_TABLE_NAME.' WHERE id IN
            (SELECT group_id FROM '.self::USER_HAS_GROUP_TABLE_NAME.' WHERE user_id=?i)';

        $groups_list = $this->_db->getAll($sql, $user->id);

        require_once("data/UserGroup.class.php");

        $user->isAdmin = false;

        foreach($groups_list as $group)
        {
            $group_id = $group['id'];
            $group_name = $group['name'];

            if($group_name=="Administrator"||$group_name=="SuperAdministrator")
            {
                $user->isAdmin = true;
            }

            $user_group = new UserGroup($group_id, $group_name);

            array_push($user->groupsList, $user_group);
        }
        return $user;
    }

    //Лучше написать Session Manager с переопределением методов, чем ифы городить

    private function setSession($userId)
    {
        $new_session_id = "";

        if(AUTH_TYPE==SERVER_SESSION)
        {
            $new_session_id = session_id();
            $_SESSION['user_id'] = $userId;
            $_SESSION['session_id'] = $new_session_id;

            setcookie("session", $new_session_id, 0, "/");

        }elseif (AUTH_TYPE==DB_SESSION_FOR_APP)
        {
            $result = null;
            try
            {
                $result = $this->_db->getRow("SELECT * FROM ".self::USER_SESSIONS_TABLE_NAME." WHERE user_id=?i", $userId);
            }
            catch (Exception $ex)
            {
                $this->setError(new ServerError($ex->getMessage()));
            }

            if(!$result)
            {
                //insert
                // Можно придумать свой генератор

                $new_session_id = session_id();

                $session_fields = array(
                    "user_id"=>$userId,
                    "session_id"=>$new_session_id
                );

                try
                {
                    $this->_db->query('INSERT INTO '.self::USER_SESSIONS_TABLE_NAME.' SET ?u', $session_fields);
                }
                catch (Exception $ex)
                {
                    $this->setError(new ServerError($ex->getMessage()));
                }

            }else
            {
                //лучше сделать warning
//                $this->setError(new ServerError("Для данного пользователя сессия уже установлена."));
//                return null;

                //Если сессия установлена возвращаем существующий идентификатор
                $new_session_id = $result["session_id"];
            }
        }

        return $new_session_id;
    }

    private function destroySession()
    {

        if(AUTH_TYPE==SERVER_SESSION)
        {
            unset($_SESSION['session_id']);
//            unset($_SESSION['isAdmin']);
            session_destroy();

            return true;

        }elseif (AUTH_TYPE==DB_SESSION_FOR_APP)
        {

            if(!$this->isRequestParamsSet(array("session")))
            {
                $this->setError(new ServerError("Не передана сессия."));
                return null;
            }

            $session = $this->getRequestParam("session");

//            print_r(CommandsServer::$user);

            //DELETE SESSION From db!!!!!
            try
            {
                $this->_db->query("DELETE FROM ".self::USER_SESSIONS_TABLE_NAME." WHERE session_id=?s", $session);
            }
            catch (Exception $ex)
            {
                $this->setError(new ServerError("Не удалось удалить сессию пользователя. \n".$ex->getMessage()));
                return null;
            }

            if($this->_db->affectedRows()!=1)
            {
                $this->setError(new ServerError("Сессия не найдена."));
                return null;
            }

            return true;

        }

    }


}