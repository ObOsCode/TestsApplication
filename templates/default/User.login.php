
<?php require_once("header.php"); ?>



<H1>Авторизация</H1>

<form class="user_form" action="<?php echo SERVER_PATH.'User/login/'?>">


    <?php if($USER->isAuth): ?>

        <p class='error'>Вы уже авторизованы</p>

    <?php else: ?>

        <?php

        if($request->getParam("action") == "login")
        {
            if($serverAnswer->type == ServerAnswer::ERROR)
            {
                $login_error_message = $serverAnswer->data->message;
                echo "<p class='error'>".$login_error_message."</p>";
            }else
            {
                header("location:".SERVER_PATH);
                exit;
            }
        }
        ?>

        <label for="login">Логин:</label>
        <input type="text" id="login" name="login" value="<?php if(!is_null($request->getParam('login'))) echo $request->getParam('login'); ?>">

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password">

        <input type="hidden" name="action" value="login">

        <a href="<?php echo SERVER_PATH.'User/registration/' ?>">Регистрация</a>
        <input class="green_button" type="submit" value="Войти">

    <?php endif; ?>



</form>

<?php require_once("footer.php"); ?>
