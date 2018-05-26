var isAnswerSelected = false;
var isSubmit = false;
var isUseHint = false;
var questionIdsList = [];
var nextQuestionIndex = 0;

var $questionResult;
var $submitButton;
var $form;
var $theoryText;
var $content;



function init(serverAnswer) {

    var data = JSON.parse(serverAnswer).data;

    var questions = data.questions;

    for(var i=0; i<questions.length; i++){
        var question = questions[i];
        questionIdsList.push(question.id)
    }

    $form = $("#question_form");
    $questionResult = $('span#question_result');
    $submitButton = $('input.green_button');
    $theoryText = $('div#theory_text');
    $content = $('div.content_wrapper');

    $('input.green_button').addClass('disabled');

    $form.submit(function (event) {
        event.stopImmediatePropagation();
        event.preventDefault();
        checkAnswer();
        return false;
    });

    $('a#show_hint').click(function (event) {
        event.stopImmediatePropagation();

        if(isSubmit || isUseHint){
            return false;
        }

        var url = "/testsApplication/Tests/getHint/";

        $.ajax({
            url: url,
            success: function(answer){
                var hintText = JSON.parse(answer).data;
                $theoryText.append("<h2>Подсказка: </h2><p>" + hintText + "</p>");
                // $('a#show_hint').unbind();
                isUseHint = true;
            }
        });

        return false;
    });

    loadNextQuestion();
}


function loadNextQuestion(id) {

    var id = questionIdsList[nextQuestionIndex];

    var url = "/testsApplication/Tests/getQuestion/?id=" + id;

    $.ajax({
        url: url,
        success: function(data){
            showQuestion(data);
        }
    });
}


function checkAnswer()
{
    if(nextQuestionIndex >= questionIdsList.length && isSubmit)
    {

        var url = "/testsApplication/Tests/addResult/";

        $.ajax({
            url: url,
            success: function(answer){

                var data = JSON.parse(answer).data;

                var trueAnswersCount = data["true_answers_count"];
                var answersCount = data["answers_count"];
                var useHintsCount = data["hints_count"];
                var totalPoints = data["total_points"];

                var backLink = "/testsApplication/Tests/getList/";

                var html = "<div id='result_container'>" +
                "<h1>Результат теста: <span class='number'>" + totalPoints + "</span> баллов</h1>" +
                "<p>Всего вопросов: <span class='number'>" + answersCount + "</span></p>" +
                "<p>Правильных ответов: <span class='number'>" + trueAnswersCount + "</span></p>" +
                "<p>Неправильных ответов: <span class='number'>" + (answersCount - trueAnswersCount) + "</span></p>" +
                "<p>Использовано подсказок: <span class='number'>" + useHintsCount + "</span></p>" +
                "<a class='green_button' href='" + backLink + "'>Назад</a>";

                // html+= ;
                // html+="<p>Всего вопросов: " + answersCount + "</p>";
                // html+="<p>Правильных ответов: " + trueAnswersCount + "</p>";
                // html+="<p>Неправильных ответов: " + (answersCount - trueAnswersCount) + "</p>";
                //
                // html+="<p>Использовано подсказок: " + useHintsCount + "</p>";
                // html+='<a href="' + backLink + '">Назад</a>';
                //
                // html+="</di>";

                $content.html(html);
            }
        });

        return;
    }

    if(!isAnswerSelected)
    {
        $questionResult.text("Выберите ответ");
        return;
    }

    if(isSubmit)
    {
        loadNextQuestion();
        return;
    }

    var selectedId = $('input[name=answer]:checked', '#question_form').val();

    var url = "/testsApplication/Tests/checkAnswer/?id=" + selectedId;

    $.ajax({
        url: url,
        success: function(answer){

            var data = JSON.parse(answer).data;

            if(data["is_correct"] == 1)
            {
                $questionResult.addClass("success");
                $questionResult.removeClass("error");
                $questionResult.text("Вы ответили правильно");

            }else
            {
                $questionResult.addClass("error");
                $questionResult.removeClass("success");
                $questionResult.text("Вы ошиблись. Правильный ответ " + data["true_answer"]);
            }

            $theoryText.text(data["theory"]);
            $submitButton.prop("value", "Продолжить");
            isSubmit = true;
        }
    });

}


function showQuestion(data) {

    var obj = JSON.parse(data);

    var questionId = obj.data.question.id;
    var answersList = obj.data.answers;
    var questionText = obj.data.question.text;

    nextQuestionIndex = obj.data.question_number + 1;
    $submitButton.prop("value", "Ответить");
    $('input.green_button').addClass('disabled');
    $questionResult.removeClass("success");
    $questionResult.removeClass("error");

    isAnswerSelected = false;
    isSubmit = false;
    isUseHint = false;

    $questionResult.text("");
    $theoryText.text("");

    var answersHTML = "";

    for(var i = 0; i < answersList.length; i++)
    {
        var answer = answersList[i];

        answersHTML+='<p class="answer">' +
            '<input class="answer_radio" type="radio" name="answer" id="answer_' + answer["id"] + '" value="' + answer["id"] + '">' +
            '<label for="answer_' + answer["id"] + '">' + answer["text"] + '</label>' +
            '</p>';
    }

    $("h2#question_number").text("Вопрос "+ (nextQuestionIndex) + " из " + questionIdsList.length);
    $("p#question_text").text(questionText);
    $("div#answers_container").html(answersHTML);

    $('input[name=answer]').change(function(event) {
        if(isSubmit){
            // event.stopImmediatePropagation();
            // event.preventDefault();
            return false;
        }
        isAnswerSelected = true;
        $('input.green_button').removeClass('disabled');
        $questionResult.text("");
    });

}
