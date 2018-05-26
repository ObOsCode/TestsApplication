<?php
require_once(SERVER_ROOT."/core/Server.class.php");
/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 02.02.2016
 * Time: 10:52
 */
class FileServer extends Server
{

    public function upload()
    {
        if(!isset($_GET['fileType'])||!isset($_GET["folder"]))
        {
            $this->setError(new ServerError("Ошибка загрузки изображения. Не переданы Get-параметры(fileType или folder)."));
            return null;
        }

        $file_data = file_get_contents( 'php://input' );

        $file_folder = SERVER_ROOT."/upload/".$_GET["folder"]."/";

        $file_name = uniqid("img_");

        if(!file_exists($file_folder))
        {
            mkdir($file_folder, 0777, true);
        }

        $file_type =  $_GET['fileType'];

        $file_path = $file_folder.$file_name.$file_type;

        if(!$fp = fopen($file_path, "w"))
        {
            $this->setError(new ServerError("Ошибка создания файла изображения."));
            return null;
        }

        $write_result = fwrite($fp,$file_data);

        if(!$write_result)
        {
            $this->setError(new ServerError("Ошибка записи файла изображения."));
            return null;
        }

        fclose($fp);

        return $file_name.$file_type;
    }

    public function remove()
    {
        if(!$this->isRequestParamsSet(array("fileName", "folder")))
        {
            $this->setError(new ServerError("Ошибка удаления файла. Переданы не все данные."));
            return null;
        }

        $folder = $this->getRequestParam("folder");
        $fileName = $this->getRequestParam("fileName");

        $file_path = SERVER_ROOT."/upload/".$folder.$fileName;

        if (file_exists($file_path))
        {
            unlink($file_path);
        }else
        {
            $this->setError(new ServerError("Ошибка удаления файла. Файл не существует."));
            return null;
        }

        return "Файл удален.";
    }

}