

<?php require_once("header.php"); ?>

<H1>Регистрация</H1>

<form class="user_form" action="<?php echo SERVER_PATH.'User/registration/'?>">

    <?php
    if($request->getParam("action") == "registration")
    {
        $message = "";
        $class = "error";

        if($serverAnswer->type == ServerAnswer::ERROR)
        {
            $message = $serverAnswer->data->message;
            $class = "error";
        }

        if($serverAnswer->type == ServerAnswer::SUCCESS)
        {
            $message = "Вы успешно зарегистрировались.";
            $class = "success";
        }

        if($class=="success")
        {
            echo "<p class='".$class."'>".$message."<br><a href='".SERVER_PATH."User/login/'>Войти</a></p>";
            exit;
        }else{
            echo "<p class='".$class."'>".$message."</p>";
        }
    }

    ?>

    <label for="name">Имя:</label>
    <input type="text" id="name" name="name" value="<?php if(!is_null($request->getParam('name'))) echo $request->getParam('name'); ?>">

    <label for="soname">Фамилия:</label>
    <input type="text" id="soname" name="soname" value="<?php if(!is_null($request->getParam('soname'))) echo $request->getParam('soname'); ?>">

    <label for="mail">Почта:</label>
    <input type="email" id="mail" name="email" value="<?php if(!is_null($request->getParam('email'))) echo $request->getParam('email'); ?>">

    <label for="login">Логин:</label>
    <input type="text" id="login" name="login" value="<?php if(!is_null($request->getParam('login'))) echo $request->getParam('login'); ?>">

    <label for="password">Пароль:</label>
    <input type="password" id="password" name="password">

    <label for="re-password">Повторите пароль:</label>
    <input type="password" id="re-password" name="rePassword">

    <p>
    <label for="capcha">Код с картинки:</label>
        <img src='<?php echo SERVER_PATH."templates/default/capcha.php"?>' id='capcha-image'>
        <input type="text" id="capcha" name="capcha">

    </p>

    <input type="hidden" name="action" value="registration">

    <a href="<?php echo SERVER_PATH.'User/login/'?>">Отмена</a>
    <input class="green_button" type="submit" value="Зарегистрироваться">

</form>

<?php require_once("footer.php"); ?>