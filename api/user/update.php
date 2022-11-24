<?php
    include "../../config/base_url.php";
    include "../../config/db.php";

    if(isset($_POST['email'], $_POST['nickname'],
    $_POST['full_name']) &&
    strlen($_POST['email']) > 0 &&
    strlen($_POST['nickname']) > 0 &&
    strlen($_POST['full_name']) > 0
    ){
        $email = $_POST['email'];
        $nickname = $_POST['nickname'];
        $full_name = $_POST['full_name'];

        session_start();
        $id = $_SESSION['user_id'];
        $query_user = mysqli_query($con, 
        "SELECT * FROM users WHERE id=$id");
        $user = mysqli_fetch_assoc($query_user);

        if(isset($_POST['old_password'], $_POST['new_password'],
        $_POST['about']) &&
        strlen($_POST['old_password']) > 0 &&
        strlen($_POST['new_password']) > 0 &&
        strlen($_POST['about']) > 0){
            if(sha1($_POST['old_password']) == $user['password']){
                $new_pass = sha1($_POST['new_password']);
                $prep = mysqli_prepare($con, 
                "UPDATE users SET nickname=?, email=?,
                full_name=?, password=?, about=?
                WHERE id=?");
                mysqli_stmt_bind_param($prep, "sssssi",
                $nickname, $email, $full_name, 
                $new_pass, $_POST['about'], $id);
                mysqli_stmt_execute($prep);
                $_SESSION["nickname"] = $nickname;
            }else{
                header("Location:$BASE_URL/edit-profile.php?error=2");
            }
        }
        elseif(isset($_POST['old_password'], $_POST['new_password']) &&
        strlen($_POST['old_password']) > 0 &&
        strlen($_POST['new_password']) > 0){
            if(sha1($_POST['old_password']) == $user['password']){
                $new_pass = sha1($_POST['new_password']);
                $prep = mysqli_prepare($con, 
                "UPDATE users SET nickname=?, email=?,
                full_name=?, password=?
                WHERE id=?");
                mysqli_stmt_bind_param($prep, "ssssi",
                $nickname, $email, $full_name, 
                $new_pass, $id);
                mysqli_stmt_execute($prep);
                $_SESSION["nickname"] = $nickname;
            }else{
                header("Location:$BASE_URL/edit-profile.php?error=2");
            }
        }elseif(isset($_POST['about']) &&
        strlen($_POST['about']) > 0){
            $prep = mysqli_prepare($con, 
            "UPDATE users SET nickname=?, email=?,
            full_name=?, about=?
            WHERE id=?");
            mysqli_stmt_bind_param($prep, "ssssi", 
            $nickname, $email, $full_name, $_POST['about'], $id);
            mysqli_stmt_execute($prep);
            $_SESSION['nickname'] = $nickname; 
        }else{
            $prep = mysqli_prepare($con, 
            "UPDATE users SET nickname=?, email=?,
            full_name=?
            WHERE id=?");
            mysqli_stmt_bind_param($prep, "sssi", 
            $nickname, $email, $full_name, $id);
            mysqli_stmt_execute($prep);
            $_SESSION['nickname'] = $nickname; 
        }

        header("Location:$BASE_URL/profile.php?nickaname=$nickname");
    }else{
        header("Location:$BASE_URL/edit-profile.php?error=1");
    }

?>