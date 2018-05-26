<?php require_once("header.php"); ?>

    <H1>Статистика</H1>

    <?php
        $type = $request->getParam("type");
    ?>

    <p id="statistic_menu">
        <a class="<?php if($type == 'tests') echo 'current'; ?>" href="<?php echo SERVER_PATH.'Admin/getStatistic/?type=tests'?>">Тесты</a>
        <a class="<?php if($type == 'questions') echo 'current'; ?>" href="<?php echo SERVER_PATH.'Admin/getStatistic/?type=questions'?>">Вопросы</a>
        <a class="<?php if($type == 'users') echo 'current'; ?>" href="<?php echo SERVER_PATH.'Admin/getStatistic/?type=users'?>">Пользователи</a>
    </p>

    <div class="content_wrapper">


        <!--        TESTS           -->

        <?php if($request->getParam("type") == "tests"): ?>

        <table>
            <tr>
                <th>Тест</th>
                <th>Результатов</th>
                <th>Максимально возможный бал</th>
                <th>Средний бал</th>
                <th>Использовано подсказок</th>
            </tr>

            <?php $index = 0; ?>

            <?php foreach ($serverAnswer->data as $test): ?>

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

                <tr class="<?php echo $class; ?>">
                    <td><?php echo $test["name"]; ?></td>
                    <td><?php echo $test["results_count"]; ?></td>
                    <td><?php echo $test["max_points"]; ?></td>
                    <td><?php echo $test["average_points"]; ?></td>
                    <td><?php echo $test["hints_count"]; ?></td>
                </tr>

            <?php endforeach; ?>

        </table>

        <?php endif; ?>


    <!--        QUESTIONS           -->

        <?php if($request->getParam("type") == "questions"): ?>

            <table>
                <tr>
                    <th>Вопрос</th>
                    <th>Тест</th>
                    <th>Ответы (всего/правильных/не правильных)</th>
                    <th>Подсказки</th>
                </tr>

                <?php $index = 0; ?>

                <?php foreach ($serverAnswer->data as $question): ?>

                    <?php

                        $answers_count = $question["answers_count"];
                        $true_answers_count = $question["true_answers_count"];
                        $false_answers_count = $answers_count - $true_answers_count;

                        $index++;

                        if(($index % 2) == 0)
                        {
                            $class = "chet";
                        }else
                        {
                            $class = "nechet";
                        }
                    ?>

                    <tr class="<?php echo $class; ?>">
                        <td><?php echo $question["text"]; ?></td>
                        <td><?php echo $question["test_name"]; ?></td>
                        <td><?php echo $answers_count."/".$true_answers_count."/".$false_answers_count; ?></td>
                        <td><?php echo $question["hints_count"]; ?></td>
                    </tr>

                <?php endforeach; ?>

            </table>

        <?php endif; ?>


        <!--        USERS           -->

        <?php if($request->getParam("type") == "users"): ?>

            <table>
                <tr>
                    <th>Пользователь</th>
                    <th>Пройдено тестов</th>
                    <th>Ответы (всего/правильных/не правильных)</th>
                    <th>Использовано подсказок</th>
                </tr>

                <?php $index = 0; ?>

                <?php foreach ($serverAnswer->data as $user): ?>

                    <?php

                    $answers_count = $user["answers_count"];
                    $true_answers_count = $user["true_answers_count"];
                    $false_answers_count = $answers_count - $true_answers_count;

                    $index++;

                    if(($index % 2) == 0)
                    {
                        $class = "chet";
                    }else
                    {
                        $class = "nechet";
                    }

                    ?>

                    <tr class="<?php echo $class; ?>">
                        <td><?php echo $user["name"]; ?></td>
                        <td><?php echo $user["results_count"]; ?></td>
                        <td><?php echo $answers_count."/".$true_answers_count."/".$false_answers_count; ?></td>
                        <td><?php echo $user["hints_count"]; ?></td>
                    </tr>

                <?php endforeach; ?>

            </table>

        <?php endif; ?>

    </div>

<?php require_once("footer.php"); ?>