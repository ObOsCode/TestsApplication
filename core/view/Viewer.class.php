<?php

/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 10.07.2015
 * Time: 15:23
 */

require_once(SERVER_ROOT."/core/view/IParser.php");
require_once(SERVER_ROOT."/core/ServerAnswer.class.php");


class Viewer
{
    private $_parser;

    private $_answerContentType;

    private $_answerFormat;

    //content types const
    const CONTENT_TYPE_JSON = "application/json";
    const CONTENT_TYPE_XML = "text/xml";
    const CONTENT_TYPE_HTML = "text/html";


    function __construct(Request $request)
    {
        $answer_format = $request->getParam("answerFormat");

        if($answer_format)
        {
            if($answer_format!==ANSWER_FORMAT_JSON && $answer_format!==ANSWER_FORMAT_XML && $answer_format!==ANSWER_FORMAT_TEMPLATE)
            {
                //Здесь надо вывести ошибку что переданный формат ответа не поддерживается
                $this->_answerFormat = DEFAULT_ANSWER_FORMAT;
            }else
            {
                $this->_answerFormat = $answer_format;
            }
        }else
        {
            $this->_answerFormat = DEFAULT_ANSWER_FORMAT;
        }

        switch($this->_answerFormat)
        {
            case ANSWER_FORMAT_JSON:
                require_once("parsers/JSONParser.class.php");
                $this->_parser = new JSONParser();
                $this->_answerContentType = self::CONTENT_TYPE_JSON;
                break;
            case ANSWER_FORMAT_XML:
                require_once("parsers/XMLParser.class.php");
                $this->_parser = new XMLParser();
                $this->_answerContentType = self::CONTENT_TYPE_XML;
                break;
            case ANSWER_FORMAT_TEMPLATE:
                require_once("parsers/HTMLParser.class.php");
                $this->_parser = new HTMLParser($request);
                $this->_answerContentType = self::CONTENT_TYPE_HTML;
                break;
        }
    }

    public function showAnswer(ServerAnswer $answer)
    {
        if(DEBUG)
        {
            /*
             * В debug режиме устнавливаем вывод в JSON*/
            header('Content-Type: '.ANSWER_FORMAT_JSON.'; charset='.CHARSET);


            echo "----------------------------DEBUG MODE--------------------\n";
            echo "\n";

            echo 'REMOTE_ADDR : '.$_SERVER['REMOTE_ADDR'];
            echo "\n";
            echo 'HTTP_USER_AGENT : '.$_SERVER['HTTP_USER_AGENT'];
            echo "\n";
            echo "\n";

            echo "COMMAND SERVER ANSWER : \n";
            echo "\n";
            echo "_______________________________ANSWER START_________________________________________\n";
            echo "\n";

            print_r($answer);

            echo "_________________________________END_______________________________________\n";

        }else
        {
            /*
             * В обычном режиме устнавливаем вывод как в настройках*/

            header('Content-Type: '.$this->_answerContentType.'; charset='.CHARSET);

            echo $this->_parser->parse($answer);
        }


    }



}