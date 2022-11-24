<?php 
    include "config/base_url.php";
    include "config/db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Редактирование профиля</title>
	<?php include "views/head.php"; ?>
</head>
<body>
    
    <?php include "views/header.php"; ?>
	<section class="container page">
		<div class="auth-form">
            <h1>Редактирование профиля</h1>
            <?php
                $id = $_SESSION['user_id'];
                $query_user = mysqli_query($con, 
                "SELECT * FROM users WHERE id=$id");
                $user = mysqli_fetch_assoc($query_user);
            ?>
			<form class="form" action="<?=$BASE_URL?>/api/user/update.php" method="POST">
                <fieldset class="fieldset">
                    <input required class="input" type="text" name="email" placeholder="Введите email" value="<?=$user['email']?>">
                </fieldset>
                <fieldset class="fieldset">
                    <input required class="input" type="text" name="full_name" placeholder="Полное имя" value="<?=$user['full_name']?>">
                </fieldset>
                <fieldset class="fieldset">
                    <input required class="input" type="text" name="nickname" placeholder="Nickname" value="<?=$user['nickname']?>">
                </fieldset>
                <fieldset class="fieldset">
                    <input class="input" type="password" name="old_password" placeholder="Введите старый пароль">
                </fieldset>
                <fieldset class="fieldset">
                    <input class="input" type="password" name="new_password" placeholder="Введите новый пароль">
                </fieldset>
                
                <fieldset>
                    <textarea name="about" cols="52" rows="4" placeholder="Введите информацию про себя"></textarea>
                </fieldset>
                <fieldset class="fieldset">
                    <button class="button" type="submit">Редактировать</button>
                </fieldset>
			</form>
		</div>
	</section>
</body>
</html>