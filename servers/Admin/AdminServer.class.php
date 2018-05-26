<?php

//require_once(SERVER_ROOT."/core/Server.class.php");
require_once(SERVER_ROOT."/servers/Tests/TestsServer.class.php");

class AdminServer extends TestsServer
{

    const ADD_ACTION = "add";
    const DELETE_ACTION = "del";

    function __construct()
    {
        parent::__construct();
    }


    //override
    public function testsList()
    {
        $action = null;
        $action_result = null;

        if ($this->isRequestParamsSet(array("action")))
        {
            $action = $this->getRequestParam("action");

            switch ($action)
            {
                case self::ADD_ACTION:
                    $action_result = $this->addTest();
                    break;

                case self::DELETE_ACTION:
                    $action_result = $this->delTest();
                    break;
            }
        }

        $result = new stdClass();
        $result->actionResult = $action_result;
        $result->testsList = $this->getList();

        return $result;
    }


    public function editTest()
    {

        $action = null;
        $action_result = null;

        if ($this->isRequestParamsSet(array("action")))
        {
            $action = $this->getRequestParam("action");

            switch ($action)
            {
                case self::ADD_ACTION:
                    $action_result = $this->addQuestion();
                    break;

                case self::DELETE_ACTION:
                    $action_result = $this->delQuestion();
                    break;
            }
        }

        $result = new stdClass();
        $result->actionResult = $action_result;
        $result->questionsList = $this->questionsList();

        return $result;
    }


    public function getStatistic()
    {

        if($this->isRequestParamsSet(array("type")))
        {
            $type = $this->getRequestParam("type");
        }else
        {
            $type = "tests";
            //Устанавливаем пааметр по умолчанию для вывода
            $this->setRequestParam("type", "tests");
        }

        switch ($type)
        {
            case "tests":
                return $this->getTestsStatistic();
                break;
            case "questions":
                return $this->getQuestionsStatistic();
                break;
            case "users":
                return $this->getUsersStatistic();
                break;
        }

    }


    ///////////////
    /// Private ///
    ///////////////


    private function getUsersStatistic()
    {
        $users_list = $this->selectAllFromTableWithFilter("users");
        $results_list = $this->selectAllFromTableWithFilter(self::RESULTS_TABLE_NAME);
        $results_answers_list = $this->selectAllFromTableWithFilter(self::RESULTS_ANSWERS_TABLE_NAME);
        $answers_list = $this->selectAllFromTableWithFilter(self::ANSWERS_TABLE_NAME);

        $users_statistic = array();

        foreach ($users_list as $user)
        {
            $user_statistic = array();

            $user_results_count = 0;
            $user_answers_count = 0;
            $user_true_answers_count = 0;
            $user_hints_count = 0;

            foreach ($results_list as $result)
            {
                if($result["user_id"] == $user["id"])
                {
                    $user_results_count++;

                    foreach ($results_answers_list as $user_answer)
                    {
                        if($user_answer["result_id"] == $result["id"])
                        {
                            $user_answers_count++;

                            foreach ($answers_list as $answer)
                            {
                                if($user_answer["answer_id"] == $answer["id"] && $answer["is_correct"])
                                {
                                    $user_true_answers_count++;
                                    break;
                                }
                            }

                            if($user_answer["use_hint"] == 1)
                            {
                                $user_hints_count++;
                            }
                        }
                    }
                }
            }

            $user_statistic["name"] = $user["name"]." ".$user["soname"];
            $user_statistic["results_count"] = $user_results_count;
            $user_statistic["answers_count"] = $user_answers_count;
            $user_statistic["true_answers_count"] = $user_true_answers_count;
            $user_statistic["hints_count"] = $user_hints_count;

            array_push($users_statistic, $user_statistic);
        }

        return $users_statistic;
    }


    private function getQuestionsStatistic()
    {
        $questions_list = $this->selectAllFromTableWithFilter(self::QUESTIONS_TABLE_NAME);
        $tests_list = $this->selectAllFromTableWithFilter(self::TESTS_TABLE_NAME);
        $results_list = $this->selectAllFromTableWithFilter(self::RESULTS_TABLE_NAME);
        $results_answers_list = $this->selectAllFromTableWithFilter(self::RESULTS_ANSWERS_TABLE_NAME);
        $answers_list = $this->selectAllFromTableWithFilter(self::ANSWERS_TABLE_NAME);

        $questions_statistic = array();

        foreach ($questions_list as $question)
        {
            $question_statistic = array();

            $question_statistic["text"] = $question["text"];

            $answers_count = 0;
            $true_answers_count = 0;
            $hints_count = 0;

//            $question_test_id = null;

            foreach ($tests_list as $test)
            {
                if($question["test_id"] == $test["id"])
                {
                    $question_statistic["test_name"] = $test["name"];
//                    $question_test_id = $test["id"];
                    break;
                }
            }

            foreach ($results_answers_list as $user_answer)
            {
                foreach ($answers_list as $answer)
                {
                    if($user_answer["answer_id"] == $answer["id"] && $answer["question_id"] == $question["id"])
                    {
                        $answers_count++;

                        if($answer["is_correct"] == 1)
                        {
                            $true_answers_count++;
                        }

                        if($user_answer["use_hint"] == 1)
                        {
                            $hints_count++;
                        }

                        break;
                    }
                }
            }



//            foreach ($results_answers_list as $user_answer)
//            {
//                foreach ($results_list as $result)
//                {
//                    if($user_answer["result_id"] == $result["id"] && $result["test_id"] == $question_test_id )
//                    {
//                        $answers_count++;
//
//                        foreach ($answers_list as $answer)
//                        {
//                            if($user_answer["answer_id"] == $answer["id"] && $answer["is_correct"] == 1)
//                            {
//                                $true_answers_count++;
//                                break;
//                            }
//                        }
//
//                        if($user_answer["use_hint"] == 1)
//                        {
//                            $hints_count++;
//                        }
//                    }
//                }
//            }

            $question_statistic["answers_count"] = $answers_count;
            $question_statistic["true_answers_count"] = $true_answers_count;
            $question_statistic["hints_count"] = $hints_count;

            array_push($questions_statistic, $question_statistic);
        }

        return $questions_statistic;
    }



    private function getTestsStatistic()
    {
        //Запрашиваем тесты на которые есть результаты
        $query = "SELECT tests.* FROM tests WHERE id IN (SELECT test_id FROM results)";

        $query_result = $this->_db->query($query);

        $tests_list = array();

        while($row = mysqli_fetch_array($query_result))
        {
            array_push($tests_list, $row);
        }

        //Запрашиваем ответы что бы знать какие правильные
        $query = "SELECT * FROM answers WHERE question_id IN (SELECT id FROM questions WHERE test_id IN (SELECT id FROM tests WHERE id IN (SELECT test_id FROM results)))";

        $query_result = $this->_db->query($query);

        $answers_list = array();

        while($row = mysqli_fetch_array($query_result))
        {
            array_push($answers_list, $row);
        }

        $user_results_list = $this->selectAllFromTableWithFilter(self::RESULTS_TABLE_NAME);
        $results_answers_list = $this->selectAllFromTableWithFilter(self::RESULTS_ANSWERS_TABLE_NAME);

        $tests_statistic = array();

        foreach ($tests_list as $test)
        {
            $test_result = array();
            $test_result["name"] = $test["name"];

            $test_results_count = 0;
            $test_hints_count = 0;
            $test_points_sum = 0;

            foreach ($user_results_list as $user_result)
            {
                if($user_result["test_id"] == $test["id"])
                {
                    $test_results_count++;

                    $test_questions_count = 0;

                    foreach ($results_answers_list as $user_answer)
                    {
                        if($user_answer["result_id"] == $user_result["id"])
                        {
                            $test_questions_count++;

                            if($user_answer["use_hint"] == 1)
                            {
                                $test_hints_count++;
                            }

                            foreach ($answers_list as $answer)
                            {
                                if($user_answer["answer_id"] == $answer["id"] && $answer["is_correct"] == 1)
                                {
                                    if($user_answer["use_hint"] == 1)
                                    {
                                        $test_points_sum += self::TRUE_ANSWER_WITH_HINT_POINTS;
                                    }else
                                    {
                                        $test_points_sum += self::TRUE_ANSWER_POINTS;
                                    }

                                    break;
                                }
                            }
                        }
                    }
                }
            }

            $test_result["hints_count"] = $test_hints_count;
            $test_result["results_count"] = $test_results_count;
            $test_result["max_points"] = $test_questions_count * self::TRUE_ANSWER_POINTS;
            $test_result["average_points"] = round($test_points_sum / $test_results_count, 2);

            array_push($tests_statistic, $test_result);
        }

        return $tests_statistic;
    }


    private function addQuestion()
    {
        $result = new stdClass();

        if(!$this->isRequestParamsSet(array("text", "id", "true_answer")))
        {
            $result->type = ServerAnswer::ERROR;
            $result->message =  "Переданы не все данные.";
            return $result;
        }

        $question_fields = array(
            'id'=>null,
            'test_id'=>$this->getRequestParam("id"),
            'text'=>$this->getRequestParam("text"),
            'hint_text'=>$this->getRequestParam("hint"),
            'theory_text'=>$this->getRequestParam("theory"),
        );

        try
        {
            $this->_db->query('INSERT INTO '.self::QUESTIONS_TABLE_NAME.' SET ?u', $question_fields);
        }
        catch (Exception $ex)
        {
            $result->type = ServerAnswer::ERROR;
            $result->message =  "Ошибка добавления в базу данных:  ". $ex->getMessage();
            return $result;
        }

        $new_question_id = $this->_db->insertId();

        $answers = $this->getRequestParam("answer");
        $true_answer_number = $this->getRequestParam("true_answer");

        foreach ($answers as $key=>$answer)
        {
            if(empty($answer))
            {
                continue;
            }

            $is_correct = 0;

            if($true_answer_number == ($key + 1))
            {
                $is_correct = 1;
            }

            $fields = array(
                "id"=>null,
                "question_id"=>$new_question_id,
                "text"=>$answer,
                "is_correct"=>$is_correct,
            );

            try
            {
                $this->_db->query('INSERT INTO '.self::ANSWERS_TABLE_NAME.' SET ?u', $fields);
            }
            catch (Exception $ex)
            {
                $result->type = ServerAnswer::ERROR;
                $result->message =  "Ошибка добавления в базу данных:  ". $ex->getMessage();
                return $result;
            }
        }

        $result->type = ServerAnswer::SUCCESS;
        $result->message =  "Вопрос добавлен";
        return $result;
    }


    private function delQuestion()
    {
        $result = new stdClass();

        if(!$this->isRequestParamsSet(array("question_id")))
        {
            $result->type = ServerAnswer::ERROR;
            $result->message =  "Не передан id вопроса.";
            return $result;
        }

        $id = $this->getRequestParam("question_id");

        if(!$this->isRowExist($this::QUESTIONS_TABLE_NAME, $id))
        {
            $result->type = ServerAnswer::ERROR;
            $result->message =  "Ошибка. Вопрос с id ".$id." не найден.";
            return $result;
        }

        $this->_db->query("DELETE FROM ".self::QUESTIONS_TABLE_NAME." WHERE id=?i", $id);

        $result->type = ServerAnswer::SUCCESS;
        $result->message =  "Вопрос удален";
        return $result;
    }


    private function delTest()
    {
        $result = new stdClass();

        if(!$this->isRequestParamsSet(array("id")))
        {
            $result->type = ServerAnswer::ERROR;
            $result->message =  "Не передан id теста.";
            return $result;
        }

        $id = $this->getRequestParam("id");

        if(!$this->isRowExist($this::TESTS_TABLE_NAME, $id))
        {
            $result->type = ServerAnswer::ERROR;
            $result->message =  "Ошибка. Тест с id ".$id." не найден.";
            return $result;
        }

        $this->_db->query("DELETE FROM ".self::TESTS_TABLE_NAME." WHERE id=?i", $id);

        $result->type = ServerAnswer::SUCCESS;
        $result->message =  "Тест удален";
        return $result;
    }


    private function addTest()
    {
        $result = new stdClass();

        if(!$this->isRequestParamsSet(array("name")))
        {
            $result->type = ServerAnswer::ERROR;
            $result->message =  "Не передано название теста.";
            return $result;
        }

        $name = $this->getRequestParam("name");

        if($this->isRowExist($this::TESTS_TABLE_NAME, $name, "name"))
        {
            $result->type = ServerAnswer::ERROR;
            $result->message =  "Ошибка. Тест с названием -".$name."- уже существует.";
            return $result;
        }

        $test_fields = array(
            'id'=>null,
            'name'=>$this->getRequestParam("name"),
            'create_date'=>null,
        );

        try
        {
            $this->_db->query('INSERT INTO '.self::TESTS_TABLE_NAME.' SET ?u', $test_fields);
        }
        catch (Exception $ex)
        {
            $result->type = ServerAnswer::ERROR;
            $result->message =  "Ошибка добавления в базу данных:  ". $ex->getMessage();
            return $result;
        }

        $result->type = ServerAnswer::SUCCESS;
        $result->message =  "Тест добавлен";
        return $result;
    }


    private function questionsList()
    {
        if(!$this->isRequestParamsSet(array("id")))
        {
            $this->setError(new ServerError("Не передан id теста."));
            return null;
        }

        $test_id = $this->getRequestParam("id");

        $tests = $this->selectAllFromTableWithFilter(self::TESTS_TABLE_NAME, array("id"=>$test_id));
        $question_list = $this->selectAllFromTableWithFilter(self::QUESTIONS_TABLE_NAME, array("test_id"=>$test_id));

        $questions_list_string = "";

        foreach ($question_list as $question)
        {
            $questions_list_string.=$question["id"].",";
        }

        $answers_list = $this->selectAllFromTableWithFilter(self::ANSWERS_TABLE_NAME, array("question_id"=>$questions_list_string));

        $result = new stdClass();
        $result->test = $tests[0];
        $result->questions = $question_list;
        $result->answers = $answers_list;

        return $result;
    }


}
