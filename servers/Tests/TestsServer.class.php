<?php

/**
 * Created by PhpStorm.
 * User: mrUser
 * Date: 09.04.2018
 * Time: 21:26
 */

require_once(SERVER_ROOT."/core/Server.class.php");


class TestsServer extends Server
{
    const TESTS_TABLE_NAME = "tests";
    const QUESTIONS_TABLE_NAME = "questions";
    const ANSWERS_TABLE_NAME = "answers";
    const RESULTS_TABLE_NAME = "results";
    const RESULTS_ANSWERS_TABLE_NAME = "results_answers";

    const CURRENT_TEST_ID = 'current_test';
    const CURRENT_QUESTION_ID = 'current_question';
    const CURRENT_QUESTION_NUMBER = 'question_number';
    const CURRENT_QUESTION_COUNT = 'questions_count';
    const USER_ANSWERS_LIST = 'answers';
    const USE_HINT = 'use_hint';

    const TRUE_ANSWER_POINTS = 2;
    const TRUE_ANSWER_WITH_HINT_POINTS = 1;


    function __construct()
    {
        parent::__construct();
    }


    public function getList()
    {
        $tests_list_result = array();

        $tests_list = $this->selectAllFromTableWithFilter(self::TESTS_TABLE_NAME);
        $question_list = $this->selectAllFromTableWithFilter(self::QUESTIONS_TABLE_NAME, null, ["id", "test_id"]);

        foreach ($tests_list as $test)
        {
            $test_id = $test["id"];

            $test_questions_count = 0;

            foreach ($question_list as $question)
            {
                $question_test_id = $question["test_id"];

                if($question_test_id == $test_id)
                {
                    $test_questions_count++;
                }
            }

            $test["questions_count"] = $test_questions_count;

            array_push($tests_list_result, $test);
        }

        return $tests_list_result;
    }


    public function getQuestion()
    {
        if(!$this->isRequestParamsSet(array("id")))
        {
            $this->setError(new ServerError("Не передан id вопроса."));
            return null;
        }

        $question_id = $this->getRequestParam("id");

        $questions = $this->selectAllFromTableWithFilter(self::QUESTIONS_TABLE_NAME, array("id"=>$question_id));

        $answers_fields = array("id", "question_id", "text");

        $answers = $this->selectAllFromTableWithFilter(self::ANSWERS_TABLE_NAME, array("question_id"=>$question_id), $answers_fields);

        $result = array(
            "question"=>$questions[0],
            "answers"=>$answers,
            "question_number"=>$_SESSION[self::CURRENT_QUESTION_NUMBER],
            "questions_count"=>$_SESSION[self::CURRENT_QUESTION_COUNT],
            "user_answers_list"=>$_SESSION[self::USER_ANSWERS_LIST],
        );

        $_SESSION[self::USE_HINT] = 0;
        $_SESSION[self::CURRENT_QUESTION_ID] = $question_id;
        $_SESSION[self::CURRENT_QUESTION_NUMBER]++;

        return $result;
    }


    public function checkAnswer()
    {
        if(!$this->isRequestParamsSet(array("id")))
        {
            $this->setError(new ServerError("Не передан id ответа."));
            return null;
        }

        $answer_id = $this->getRequestParam("id");
        $current_question_id = $_SESSION[self::CURRENT_QUESTION_ID];

        //Is correct
//        $answers = $this->selectAllFromTableWithFilter(self::ANSWERS_TABLE_NAME, array("id"=>$answer_id, "question_id"=>$current_question_id), array("text", "is_correct"));
        //Get theory
        $theory_texts = $this->selectAllFromTableWithFilter(self::QUESTIONS_TABLE_NAME, array("id"=>$current_question_id), array("theory_text"));

        $true_answers = $this->selectAllFromTableWithFilter(self::ANSWERS_TABLE_NAME, array("is_correct"=>1, "question_id"=>$current_question_id), array("id", "text"));

        $is_correct = $true_answers[0]["id"] == $answer_id;

        $user_answer = array(
            "answer_id"=>$answer_id,
            "use_hint"=>$_SESSION[self::USE_HINT],
            "is_correct"=>$is_correct,
//            "is_correct"=>$answers[0]["is_correct"],
        );

        //Put answer to seesion object
        $user_answers_list = $_SESSION[self::USER_ANSWERS_LIST];
        array_push($user_answers_list, $user_answer);
        $_SESSION[self::USER_ANSWERS_LIST] = $user_answers_list;

        $result = array(
            "is_correct"=>$is_correct,
//            "is_correct"=>$answers[0]["is_correct"],
            "theory"=>$theory_texts[0]["theory_text"],
            "true_answer"=>$true_answers[0]["text"],
        );

        return $result;
    }


    public function getTest()
    {
        if(!$this->isRequestParamsSet(array("id")))
        {
            $this->setError(new ServerError("Не передан id теста."));
            return null;
        }

        $test_id = $this->getRequestParam("id");

        $tests = $this->selectAllFromTableWithFilter(self::TESTS_TABLE_NAME, array("id"=>$test_id));

        $test = $tests[0];

        $question_list = $this->selectAllFromTableWithFilter(self::QUESTIONS_TABLE_NAME, array("test_id"=>$test["id"]), array("id"));

        $test["questions"] = $question_list;

        $_SESSION[self::CURRENT_TEST_ID] = $test_id;
        $_SESSION[self::CURRENT_QUESTION_COUNT] = count($question_list);
        $_SESSION[self::CURRENT_QUESTION_NUMBER] = 0;
        $_SESSION[self::USER_ANSWERS_LIST] = array();

        return $test;
    }


    public function getHint()
    {
        if($_SESSION[self::USE_HINT] == 1)
        {
            $this->setError(new ServerError("Вы уже использовали подсказку."));
            return null;
        }

        $question_id = $_SESSION[self::CURRENT_QUESTION_ID];

        $question_list = $this->selectAllFromTableWithFilter(self::QUESTIONS_TABLE_NAME, array("id"=>$question_id), array("hint_text"));

        $_SESSION[self::USE_HINT] = 1;

        return $question_list[0]["hint_text"];
    }


    public function addResult()
    {
        $result_fields = array(
            'id'=>null,
            'user_id'=>CommandsServer::$user->id,
            'test_id'=>$_SESSION[self::CURRENT_TEST_ID],
            'date'=>null,
        );

        try
        {
            $this->_db->query('INSERT INTO '.self::RESULTS_TABLE_NAME.' SET ?u', $result_fields);
        }
        catch (Exception $ex)
        {
            $this->setError(new ServerError($ex->getMessage()));
            return null;
        }

        $new_result_id = $this->_db->insertId();

        $user_answers = $_SESSION[self::USER_ANSWERS_LIST];
//        $user_answers = $_SESSION[self::USER_ANSWERS_LIST];

        $answers_count = count($user_answers);
        $true_answers_count = 0;
        $hints_count = 0;
        $total_points = 0;

        foreach ($user_answers as $answer)
        {
            $result_answer_fields = array(
                'id'=>null,
                'result_id'=>$new_result_id,
                'answer_id'=>$answer["answer_id"],
                'use_hint'=>$answer["use_hint"],
            );

            try
            {
                $this->_db->query('INSERT INTO '.self::RESULTS_ANSWERS_TABLE_NAME.' SET ?u', $result_answer_fields);
            }
            catch (Exception $ex)
            {
                $this->setError(new ServerError($ex->getMessage()));
                return null;
            }

            if($answer["is_correct"]==true)
            {
                $true_answers_count++;

                if($answer["use_hint"]==1)
                {
                    $total_points += self::TRUE_ANSWER_WITH_HINT_POINTS;
                }else
                {
                    $total_points += self::TRUE_ANSWER_POINTS;
                }
            }

            if($answer["use_hint"]==1)
            {
                $hints_count++;
            }

        }

        $result = array(
//            "id"=>$new_result_id,
//            "answers"=>$user_answers,
            "total_points"=>$total_points,
            "answers_count"=>$answers_count,
            "true_answers_count"=>$true_answers_count,
            "hints_count"=>$hints_count,
        );

        return $result;

    }


    public function getResults()
    {
        $user_id = CommandsServer::$user->id;

        $results_list = $this->selectAllFromTableWithFilter(self::RESULTS_TABLE_NAME, array("user_id"=>$user_id));

        //Запрашиваем тесты которые прошел пользователь
        $tests_list = array();

        $query = "SELECT * FROM tests WHERE id IN (SELECT test_id FROM results WHERE user_id=".$user_id.");";
        $query_result = $this->_db->query($query);

        while($row = mysqli_fetch_array($query_result))
        {
            array_push($tests_list, $row);
        }

        $results_answers_list = array();

        $query = "SELECT * FROM results_answers WHERE result_id IN (SELECT id FROM results WHERE user_id=".$user_id.")";
        $query_result = $this->_db->query($query);

        while($row = mysqli_fetch_array($query_result))
        {
            array_push($results_answers_list, $row);
        }

        $answers_list = $this->selectAllFromTableWithFilter(self::ANSWERS_TABLE_NAME);

        $user_results_list = array();

        foreach ($results_list as $result)
        {
            $user_result = array();

            $user_results_count = 0;
            $user_answers_count = 0;
            $user_true_answers_count = 0;
            $user_hints_count = 0;
            $user_points = 0;

            foreach ($tests_list as $test)
            {
                if($result["test_id"] == $test["id"])
                {
                    $user_results_count++;
                    $user_result["date"] = $result["date"];

                    foreach ($results_answers_list as $user_answer)
                    {
                        if($user_answer["result_id"] == $result["id"])
                        {
                            $user_answers_count++;

                            foreach ($answers_list as $answer)
                            {
                                if($user_answer["answer_id"] == $answer["id"] && $answer["is_correct"] == 1)
                                {
                                    $user_true_answers_count++;

                                    if($user_answer["use_hint"] == 1)
                                    {
                                        $user_points += self::TRUE_ANSWER_WITH_HINT_POINTS;
                                    }else
                                    {
                                        $user_points += self::TRUE_ANSWER_POINTS;
                                    }

                                    break;
                                }
                            }

                            if($user_answer["use_hint"] == 1)
                            {
                                $user_hints_count++;
                            }
                        }
                    }

                    break;
                }
            }


            $user_result["test_name"] = $test["name"];
            $user_result["results_count"] = $user_results_count;
            $user_result["answers_count"] = $user_answers_count;
            $user_result["true_answers_count"] = $user_true_answers_count;
            $user_result["hints_count"] = $user_hints_count;
            $user_result["points"] = $user_points;
            $user_result["max_points"] = $user_answers_count * self::TRUE_ANSWER_POINTS;

            array_push($user_results_list, $user_result);
        }

        return $user_results_list;
    }

}