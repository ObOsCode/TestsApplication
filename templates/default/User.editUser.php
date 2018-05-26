<?php require_once("header.php"); ?>

<H1>Редактировать учетную запись</H1>

<div class="content_wrapper">

<?php //print_r($serverAnswer); ?>


    <?php

    $action = $request->getParam("action");

    if($action == "edit")
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
            $message = "Изменения сохранены.";
            $class = "success";
        }

        echo "<p class='".$class."'>".$message."</p>";
    }
    ?>

    <?php
    $user_id = $request->getParam("id");
    ?>

    <form id="edit_user" action="<?php echo SERVER_PATH.'User/editUser/'?>">

        <label for="name">Имя:</label>
        <input type="text" id="name" name="name" value="<?php if(!is_null($request->getParam('name'))) echo $request->getParam('name'); ?>">

        <label for="soname">Фамилия:</label>
        <input type="text" id="soname" name="soname" value="<?php if(!is_null($request->getParam('soname'))) echo $request->getParam('soname'); ?>">

        <label for="mail">Почта:</label>
        <input type="email" id="mail" name="email" value="<?php if(!is_null($request->getParam('email'))) echo $request->getParam('email'); ?>">

        <label for="login">Логин:</label>
        <input type="text" id="login" name="login" value="<?php if(!is_null($request->getParam('login'))) echo $request->getParam('login'); ?>">

        <label for="password">Сменить пароль на:</label>
        <input type="password" id="password" name="password">

        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" value="<?php echo $user_id; ?>">

        <p>
            <a href="<?php echo SERVER_PATH.'User/getUserList/'?>">Отмена</a>
            <input class="green_button" type="submit" value="Применить">
        </p>

    </form>


</div>

<?php require_once("footer.php"); ?>