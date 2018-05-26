<?php require_once("header.php"); ?>

<H1>Ваши результаты</H1>

<div class="content_wrapper">

    <table>
        <tr class="header">
            <th>Тест</th>
            <th>Дата</th>
            <th>Ответы (всего/правильных/не правильных)</th>
            <th>Использовано подсказок</th>
            <th>Баллы</th>
            <th>Максимально баллов</th>
        </tr>

        <?php $index = 0; ?>

        <?php foreach ($serverAnswer->data as $test): ?>

            <?php
            $answers_count = $test["answers_count"];
            $true_answers_count = $test["true_answers_count"];
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
                <td><?php echo $test["test_name"]; ?></td>
                <td><?php echo $test["date"]; ?></td>
                <td><?php echo $answers_count."/".$true_answers_count."/".$false_answers_count; ?></td>
                <td><?php echo $test["hints_count"]; ?></td>
                <td><?php echo $test["points"]; ?></td>
                <td><?php echo $test["max_points"]; ?></td>
            </tr>

        <?php endforeach; ?>

    </table>

</div>

<?php require_once("footer.php"); ?>