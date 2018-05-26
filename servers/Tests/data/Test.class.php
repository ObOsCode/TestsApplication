<?php

/**
 * Created by PhpStorm.
 * User: mrUser
 * Date: 14.04.2018
 * Time: 23:30
 */
class Test
{
    private $_id;
    private $_name;
    private $_questionsList = array();

    function __construct($id, $name, array $questionsList)
    {
        $this->_id = $id;
        $this->_name = $name;
        $this->_questionsList = $questionsList;
    }


    public function getId()
    {
        return $this->_id;
    }


    public function getName()
    {
        return $this->_name;
    }


    public function getQuestionsList()
    {
        return $this->_questionsList;
    }

}