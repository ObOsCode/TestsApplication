
<html>

<head>

    <link href="style.css" rel="stylesheet">

</head>

<body>

    <header>



        <div id="header_wrapper">
            <div id="app_info">
                <p id="name">"Архитектура компьютера"</p>
                <p id="description">Система тестирования с обучением</p>
            </div>

            <?php if($user_is_auth): ?>
            <div id="login_menu"><p>Пользователь | <a href="login.php">Выйти</a> </p></div>
            <?php endif; ?>

        </div>
        <hr>



    </header>