
<?php require_once("header.php"); ?>

<H1>Регистрация</H1>

<form action="registration.php" class="user_form">

    <label for="name">Имя:</label>
    <input type="text" id="name">

    <label for="soname">Фамилия:</label>
    <input type="text" id="soname">

    <label for="mail">Почта:</label>
    <input type="email" id="mail">

    <label for="login">Логин:</label>
    <input type="text" id="login">

    <label for="password">Пароль:</label>
    <input type="password" id="password">

    <label for="re-password">Повторите пароль:</label>
    <input type="password" id="re-password">

    <a href="login.php">Отмена</a>
    <input class="green_button" type="submit" value="Зарегистрироваться">

</form>

<?php require_once("footer.php"); ?>
