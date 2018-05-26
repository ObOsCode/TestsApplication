<?php require_once("header.php"); ?>

<?php
$questions_data = $serverAnswer->data->questionsList;
$test = $questions_data->test;
$questions = $questions_data->questions;
$answers = $questions_data->answers;
?>

    <H1><?php echo $test["name"]; ?></H1>

    <div class="content_wrapper">

        <h2>Новый вопрос:</h2>

        <form id="add_question" action="<?php echo SERVER_PATH.'Admin/editTest/'?>">

            <?php

            if(!is_null($request->getParam("action")) && !empty($request->getParam("action")))
            {
                $action_message = $serverAnswer->data->actionResult->message;

                if($serverAnswer->data->actionResult->type == ServerAnswer::ERROR)
                {
                    echo "<p class='error'>".$action_message."</p>";
                }else
                {
                    echo "<p class='success'>".$action_message."</p>";
                }
            }

            ?>

            <label for="text">Текст вопроса:</label>
            <textarea name="text"> </textarea>

            <label for="hint">Текст подсказки:</label>
            <textarea name="hint"> </textarea>

            <label for="theory">Теория:</label>
            <textarea name="theory"> </textarea>

            <label for="answer">Ответы:</label>

            <ul>
                <li><input type="text" name="answer[]"></li>
                <li><input type="text" name="answer[]"></li>
                <li><input type="text" name="answer[]"></li>
                <li><input type="text" name="answer[]"></li>
                <li><input type="text" name="answer[]"></li>
            </ul>

            <label for="true_answer">Номер правильного ответа:</label>
            <input type="text" name="true_answer">

            <input type="hidden" name="action" value="add">
            <input type="hidden" name="id" value="<?php echo $test["id"]; ?>">

            <p>
                <input class="green_button" type="submit" value="Добавить">
            </p>

        </form>
    </div>

    <div class="content_wrapper">

        <h2>Вопросы:</h2>

        <ul id="admin_questions_list" class="list">

            <?php $index = 0; ?>

            <?php foreach ($questions as $question): ?>

            <?php

                $index++;

                if(($index % 2) == 0)
                {
                    $class = "chet";
                }else
                {
                    $class = "nechet";
                }

                ?>

                <li class="<?php echo $class; ?>">

                    <p class="name"><?php echo $index.". ".$question["text"]; ?></p>

                    <ul class="answers_list">

                        <?php foreach ($answers as $answer):?>

                            <?php if ($answer["question_id"] == $question["id"]):?>
                                <?php
                                    if($answer["is_correct"] ==1 )
                                    {
                                        $class = "correct";
                                    }else{
                                        $class = "not_correct";
                                    }
                                ?>
                                <li class="<?php echo $class; ?>"> <?php echo $answer["text"]; ?></li>
                            <?php endif; ?>

                        <?php endforeach; ?>

                    </ul>

                    <a class="green_button" href="<?php echo SERVER_PATH.'Admin/editTest/?id='.$test["id"].'&action=del&question_id='.$question["id"]; ?>">Удалить</a>

<!--                    <ul>-->
<!--                        <li class="name">--><?php //echo $index.". ".$question["text"]; ?>
<!---->
<!--                            <ul class="answers_list">-->
<!---->
<!--                                --><?php //foreach ($answers as $answer):?>
<!---->
<!--                                    --><?php //if ($answer["question_id"] == $question["id"]):?>
<!--                                        <li> --><?php //echo $answer["text"]." | "; ?><!--</li>-->
<!--                                    --><?php //endif; ?>
<!---->
<!--                                --><?php //endforeach; ?>
<!---->
<!--                            </ul>-->
<!---->
<!--                        </li>-->
<!--                        <li class="action_button"><a class="green_button" href="--><?php //echo SERVER_PATH.'Admin/editTest/?id='.$test["id"].'&action=del&question_id='.$question["id"]; ?><!--">Удалить</a></li>-->
<!--                    </ul>-->
                </li>

            <?php endforeach; ?>

        </ul>

    </div>

<?php require_once("footer.php"); ?>