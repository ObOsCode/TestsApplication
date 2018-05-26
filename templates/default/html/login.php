
<?php require_once("header.php"); ?>


    <H1>Авторизация</H1>

    <form class="user_form" action="list.php" method="post">

        <label for="login">Логин:</label>
        <input type="text" id="login">

        <label for="password">Пароль:</label>
        <input type="password" id="password">

        <a href="registration.php">Регистрация</a>
        <input class="green_button" type="submit" value="Войти">

    </form>

<?php require_once("footer.php"); ?>

