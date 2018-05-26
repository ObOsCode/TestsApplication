<?php
    $TEMPLATE_PATH = SERVER_PATH."/templates/".TEMPLATE."/";
    $USER = CommandsServer::$user;
?>

<html>

<head>

    <link href="<?php echo $TEMPLATE_PATH; ?>style.css" rel="stylesheet">

    <?php if($USER->isAdmin):?>

        <link href="<?php echo $TEMPLATE_PATH; ?>admin.css" rel="stylesheet">

    <?php endif; ?>

</head>

<body>

    <header>

        <div id="header_wrapper">
            <div id="app_info">
                <p id="name">Система тестирования</p>
                <p id="description">с элементами обучения</p>
            </div>

            <?php if($USER->isAuth): ?>

                <div id="login_menu">
                    <p>
                        <img id="user_avatar" src="<?php echo $USER->avatarURL; ?>">
                        <?php echo $USER->name." ".$USER->soname; ?> | <a href="<?php echo SERVER_PATH.'User/logout/'?>">Выйти</a>
                    </p>
                </div>


            <?php endif; ?>

        </div>

        <hr>

        <?php if($USER->isAuth): ?>

        <div id="main_menu">
            <p>
                <a class="main_menu" href="<?php echo SERVER_PATH.'Tests/getList/'?>">Тесты</a> |
                <a class="main_menu" href="<?php echo SERVER_PATH.'Tests/getResults/'?>">Результаты</a> |
                <?php if($USER->isAdmin): ?>
                    <a class="main_menu" href="<?php echo SERVER_PATH.'User/getUserList/'?>">Пользователи</a> |
                    <a class="main_menu" href="<?php echo SERVER_PATH.'Admin/testsList/'?>">Редактировать тесты</a> |
                    <a class="main_menu" href="<?php echo SERVER_PATH.'Admin/getStatistic/'?>">Статистика</a>
                <?php endif; ?>
            </p>
        </div>

        <?php endif; ?>


    </header>

    <div id="content">