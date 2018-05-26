<?php require_once("header.php"); ?>

    <H1>Выберите тест</H1>

    <div class="content_wrapper">

        <ul id="tests_list" class="list">

            <?php

                $testList = $serverAnswer->data;

                $index = 0;

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
                            <li class="action_button"><a class="green_button" href="<?php echo SERVER_PATH.'Tests/getTest/?id='.$test_id; ?>">Пройти тест</a></li>
                        </ul>
                    </li>

                <?php endforeach; ?>

        </ul>

    </div>

<?php require_once("footer.php"); ?>