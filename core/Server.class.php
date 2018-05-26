<?php
/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 02.07.2015
 * Time: 15:29
 */

require_once("DB.class.php");

class Server {

    //Экземпляр класса BD(стыренного с интернетов)
    // для работы с Базой данных
    protected $_db;

    //Объект с параметрами запроса переданными
    // клиентом Get или Post
    private $_request;

    //Ссылка на объект конфигурации сервера
    protected $_config;


    //private $_result;

    private $_error;


    function __construct()
    {

        //Проверяем наличие кастомного файла конфигурации для
        //данного сервера. Если такового нет - используем
        // конфигурацию по умолчанию(ServerConfigBase.class.php).


        //Путь для проверки
        $file_path = $this->getFilePath()."/ServerConfig.class.php";

        //Импортируем базовый класс настроек т.к. от
        // него наследуется ServerConfig класс
        //(Возможно импорт лучше сделать в самом классе ServerConfig
        //но тогда можно забыть это сделать в дальнейшем, в файлах конфигурации
        //других серверов. Думаю, лучше оставить как сейчас.)
        require_once("ServerConfigBase.class.php");

        if(file_exists($file_path))
        {
            require_once($file_path);
            $server_config = new ServerConfig();
        }else
        {
            $server_config = new ServerConfigBase();
        }

        $this->_config = $server_config;

        $db_config = array(
            'host'=>$server_config->db_host,
            'db'=>$server_config->db_name,
            'User'=>$server_config->db_user,
            'pass'=>$server_config->db_password
        );

        $this->_db = new DB($db_config);
    }

    ////////////////////
    //Public
    ///////////////////

    public function setRequest(Request $request )
    {
        $this->_request = $request;
    }


    //Установка параметров запроса вручную
    //Для выполнения одних команд сервера из тела других
    public function setRequestParam($paramName, $paramValue)
    {
        $this->_request->setParam($paramName, $paramValue);
    }


    //Возвращает объект с конфигурацией текущего сервера
    public function getConfig()
    {
        return $this->_config;
    }


    public function getError()
    {
        return $this->_error;
    }


    ////////////////////
    //Protected
    ///////////////////

    protected function setError(ServerError $error)
    {
        $this->_error = $error;

        return;
    }


    //Проверяет существует ли запись в таблице $table с значением поля $field_name
    //равным $field_value
    protected function isRowExist($table, $field_value, $field_name = "id")
    {
        try
        {
            $results = $this->_db->getAll("SELECT * FROM " . $table . " WHERE $field_name=?s", $field_value);
        }
        catch (Exception $ex)
        {
            $this->setError(new ServerError($ex->getMessage()));
        }

        return (count($results)>0);
    }


    protected function editTableRow($table, $row_id, $fields)
    {
        if(count($fields)==0)
        {
            $this->setError(new ServerError("Не передан ни один параметр для редактирования."));
            return;
        }

        try
        {
            $this->_db->query("UPDATE ".$table." SET ?u  WHERE id=?i", $fields, $row_id);
        }
        catch (Exception $ex)
        {
            $this->setError(new ServerError($ex->getMessage()));
        }
    }


    protected function selectAllFromTableWithFilter($table, $filter_fields = null, $columns_list = null)
    {
        //$filter_fields - массив содержащий параметры фильтра в формате
        //$filter_field["id"]=40;
        //$filter_field["id"]="40,25,98";
        //$filter_field["email"]=mymail@host.ru;
        //$filter_field["email"]="mymail@host.ru, othermail@host2.ru";
        //$filter_field["id"]=">5";
        //$filter_field["id"]="<=40";
        //и т.д.

        $filter_srt="";

        if($filter_fields && (count($filter_fields)>0))
        {
            $filter_srt.=" WHERE ";

            $i=count($filter_fields);

            foreach($filter_fields as $key => $value)
            {
                $sub_fields = explode(",", $value);

                $j = count($sub_fields);

                if($j>1)
                {
                    $filter_srt.="(";

                    foreach($sub_fields as $key2 => $value2)
                    {
                        $filter_srt.= $key.'="'.$value2.'"';;

                        if($j>1)
                        {
                            $filter_srt.= " OR ";
                        }
                        $j--;
                    }

                    $filter_srt.=")";
                }else
                {
                    if(strrpos($value,">")!==false
                        ||strrpos($value,"<")!==false
                        ||strrpos($value,"<=")!==false
                        ||strrpos($value,">=")!==false)
                    {
                        $filter_srt.= $key.$value;
                    }else
                    {
                        $filter_srt.= $key.'="'.$value.'"';
                    }
                }

                if($i>1)
                {
                    $filter_srt.= " AND ";
                }
                $i--;
            }
        }

        $columns_str = "";

        if($columns_list && (count($columns_list)>0))
        {
            $columns_str.= implode(",", $columns_list);
        }else
        {
            $columns_str = "*";
        }

        $sql = "SELECT ".$columns_str." FROM ".$table.$filter_srt;

//        echo("where_srt - ".$filter_srt."\n");
//        echo("sql - ".$sql."\n");

        $users_list = $this->_db->getAll($sql);

        return $users_list;
    }


    //Возвращает значение параметра если он передан,
    //а если не передан - NULL
    protected function getRequestParam($paramName)
    {
        return $this->_request->getParam($paramName);
    }


    //Проверяет переданы ли в запросе параметры из списка $paramsList
    protected function isRequestParamsSet(array $paramsList)
    {
        foreach($paramsList as $param_name)
        {
            if(!$this->getRequestParam($param_name))
            {
                //Если хоть один параметр не передан возвращаем false
                return false;
            }
        }
        return true;
    }


    ////////////////////
    //Private
    ////////////////////


    //Возвращает путь до текущего файла
    private function getFilePath()
    {
        $reflect =  new ReflectionClass($this);
        return dirname($reflect->getFileName());
    }


}//class