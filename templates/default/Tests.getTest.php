<?php require_once("header.php"); ?>

    <?php
        require_once(SERVER_ROOT."/core/view/parsers/JSONParser.class.php");

        $parser = new JSONParser();

        $data_json_string = $parser->parse($serverAnswer);

        $test_data = $serverAnswer->data;

//        print_r($test_data);

    ?>

    <H1><?php echo $test_data["name"]; ?></H1>

    <div class="content_wrapper">

        <form id="question_form" action="question.php">

            <h2 id="question_number">Вопрос 1</h2>

            <hr>

            <p id="question_text"></p>

            <div id="answers_container">
                <p class="answer">
                    <input type="radio" id="answer_1"
                           name="contact" value="1">
                    <label for="answer_1">Первый вариант ответа</label>
                </p>

                <p class="answer">
                    <input type="radio" id="answer_2"
                           name="contact" value="2">
                    <label for="answer_2">Второй вариант ответа</label>
                </p>

                <p class="answer">
                    <input type="radio" id="answer_3"
                           name="contact" value="3">
                    <label for="answer_3">Третий вариант</label>
                </p>
            </div>

            <p>
                <input class="green_button" type="submit" value="Ответить">
                <span id="question_result"></span>
                <a id="show_hint" href="#">Подсказка</a>
            </p>

            <div id="theory_text"></div>

        </form>

    </div>

    <script src="<?php echo $TEMPLATE_PATH; ?>js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo $TEMPLATE_PATH; ?>js/main.js"></script>

    <script>

        $(document).ready(function() {

            init('<?php echo $data_json_string; ?>');
        });


    </script>

<?php require_once("footer.php"); ?>