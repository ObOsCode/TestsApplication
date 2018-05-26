<?php require_once("header.php"); ?>

<H1>Пользователи</H1>

<div class="content_wrapper">

<?php //print_r($serverAnswer); ?>

    <?php

    $action = $request->getParam("action");

    if($action == "del" || $action == "edit")
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
            if ($action == "del")
            {
                $message = "Пользователь удален.";
            }
            if ($action == "edit")
            {
                $message = "Учетная запись отредактирована.";
            }
            $class = "success";
        }

        echo "<p class='".$class."'>".$message."</p>";
    }
    ?>


    <table>
        <tr>
            <th>Имя</th>
            <th>Логин</th>
            <th>Почта</th>
            <th>Редактировать</th>
            <th>Удалить</th>

        </tr>

        <?php $index = 0; ?>

        <?php foreach ($serverAnswer->data as $user): ?>

            <?php

            $user_name = $user["name"]." ".$user["soname"];

            $index++;

            if(($index % 2) == 0)
            {
                $class = "chet";
            }else
            {
                $class = "nechet";
            }
            ?>

            <tr class="<?php echo $class." ".$user['id']; ?>">
                <td><?php echo $user_name; ?></td>
                <td><?php echo $user["login"]; ?></td>
                <td><?php echo $user["email"]; ?></td>
                <?php
                $edit_url=SERVER_PATH.'User/editUser/?id='.$user['id'].'&name='.$user["name"].'&soname='.$user["soname"].'&login='.$user["login"].'&email='.$user["email"];
                ?>
                <td><a class="green_button edit" href="<?php echo $edit_url; ?>">Редактировать</a> </td>
                <td><a id="<?php echo $user['id']; ?>" class="green_button del" href="<?php echo SERVER_PATH.'User/removeUser/?action=del&id='.$user['id']; ?>">Удалить</a> </td>
            </tr>

        <?php endforeach; ?>

    </table>

</div>

    <script src="<?php echo $TEMPLATE_PATH; ?>js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo $TEMPLATE_PATH; ?>js/users_list.js"></script>

    <script>

        $(document).ready(function() {

            init();
//            init('<?php //echo $data_json_string; ?>//'/);
        });


    </script>

<?php require_once("footer.php"); ?>