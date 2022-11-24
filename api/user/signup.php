<?php
    include "../../config/base_url.php";
    include "../../config/db.php";

    if(isset($_POST["email"],
        $_POST["nickname"],
        $_POST["full_name"],
        $_POST["password"],
        $_POST["password2"]) &&
        strlen($_POST["email"]) > 0 &&
        strlen($_POST["nickname"]) > 0 &&
        strlen($_POST["full_name"]) > 0 &&
        strlen($_POST["password"]) > 0 &&
        strlen($_POST["password2"]) > 0 
    ){
        $email = $_POST["email"];
        $nickname = $_POST["nickname"];
        $full_name = $_POST["full_name"];
        $password = $_POST["password"];
        $password2 = $_POST["password2"];
        $hash = sha1($password);

        if($password != $password2) {
            header("Location: $BASE_URL/register.php?error=2");
        }

        $prep = mysqli_prepare($con, "SELECT * FROM users WHERE email = ? AND nickname = ?");
        mysqli_stmt_bind_param($prep, "ss", $email, $hash);
        mysqli_stmt_execute($prep);
        $query1 = mysqli_stmt_get_result($prep);

        if(mysqli_num_rows($query1) > 0) {
            header("Location: $BASE_URL/register.php?error=3");
            exit();
        }

        $prep1 = mysqli_prepare($con, "INSERT INTO users (email, nickname, full_name, password) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($prep1, 'ssss', $email, $nickname, $full_name, $hash);
        mysqli_stmt_execute($prep1);

        header("Location:$BASE_URL/login.php");

    }else {
        header("Location: $BASE_URL/register.php?error=1");
    }
?>