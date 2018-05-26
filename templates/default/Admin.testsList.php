<?php require_once("header.php"); ?>

    <H1>Редактор тестов</H1>


    <div class="content_wrapper">

        <form id="add_test" action="<?php echo SERVER_PATH.'Admin/testsList/'?>">

            <?php

            if(!is_null($request->getParam("action")) && !empty($request->getParam("action")))
            {
                $action_message = $serverAnswer->data->actionResult->message;

                if($serverAnswer->data->actionResult->type == ServerAnswer::ERROR)
                {
                    echo "<p class='form_error'>".$action_message."</p>";
                }else
                {
                    echo "<p class='form_success'>".$action_message."</p>";
                }
            }

            ?>

            <label for="name">Название:</label>
            <input type="text" id="name" name="name">

            <input type="hidden" name="action" value="add">

            <input class="green_button" type="submit" value="Добавить">

        </form>


    </div>

    <div class="content_wrapper">

        <ul id="tests_list" class="list">

            <?php

            $testList = $serverAnswer->data->testsList;

            $index = 0;

            if(count($testList)>0):?>

                <?php

                foreach($testList as $test):

                    $questions_count = $test["questions_count"];

                    $test_name = $test["name"];
                    $test_id = $test["id"];

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
                        <ul>
                            <li class="name"><?php echo $test_name; ?></li>
                            <li class="questions_count">Вопросов: <?php echo $questions_count; ?></li>
                            <li class="action_button"><a class="green_button" href="<?php echo SERVER_PATH.'Admin/editTest/?id='.$test_id; ?>">Ред.</a></li>
                            <li class="action_button"><a class="green_button" href="<?php echo SERVER_PATH.'Admin/testsList/?action=del&id='.$test_id; ?>">Удалить</a></li>
                        </ul>
                    </li>

                <?php endforeach; ?>

            <?php else:?>

                <p>Нет ни одного теста</p>

            <?php endif; ?>

        </ul>

    </div>

<?php require_once("footer.php"); ?>