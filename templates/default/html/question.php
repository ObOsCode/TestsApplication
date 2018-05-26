<?php $user_is_auth = true; ?>

<?php require_once("header.php"); ?>

<H1>Название теста</H1>


<div class="content_wrapper">

    <form id="question_form" action="question.php">

        <h2>Вопрос №4</h2>

        <hr>

        <p>Текст вопроса очень длинный текст, он даже  может не поместится в одну строкук, но наша верстка
        это предусматривает и картинка  выглядит замечательно.
        </p>

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

        <p>
            <input class="green_button" type="submit" value="Далее">
            <a href="#">Подсказка</a>
        </p>
    </form>

</div>

<?php require_once("footer.php"); ?>

