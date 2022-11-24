<?php
    include "../../config/base_url.php";
    include "../../config/db.php";

    if(isset($_POST['title'], $_POST['description'], 
    $_POST['category_id'], $_GET['id']) &&
    strlen($_POST['title']) > 0 &&
    strlen($_POST['description']) > 0 &&
    intval($_POST['category_id']) &&
    intval($_GET['id'])){
        $title = $_POST['title'];
        $desc = $_POST['description'];
        $cat_id = $_POST['category_id'];
        $id = $_GET['id'];
        session_start();
        $author_id = $_SESSION['user_id'];

        if(isset($_FILES['image'], $_FILES['image']['name'])){
            $query = mysqli_query($con, 
            "SELECT img FROM blogs WHERE id = $id");
            if(mysqli_num_rows($query) > 0){
                $blog_img = mysqli_fetch_assoc($query);
                $old_path = "../../".$blog_img['img'];
                if(file_exists($old_path)){
                    unlink($old_path);
                }
            }

            // 1.png ===> 4267894089718.png
            $ext = end(explode(".", $_FILES['image']['name']));
            $image_name = time().".".$ext;
            move_uploaded_file($_FILES['image']['tmp_name'],
             "../../images/blogs/$image_name");
            $path = "images/blogs/$image_name";

            $prep = mysqli_prepare($con, 
            "UPDATE blogs SET title=?, description=?,
            img=?, category_id=? WHERE id=?");
            mysqli_stmt_bind_param($prep, "sssii", 
            $title, $desc, $path, $cat_id, $id);
            mysqli_stmt_execute($prep);
        }else{
            $prep = mysqli_prepare($con, 
            "UPDATE blogs SET title=?, description=?,
            category_id=? WHERE id=?");
            mysqli_stmt_bind_param($prep, "ssii", 
            $title, $desc, $cat_id, $id);
            mysqli_stmt_execute($prep);
        }
        $nickname = $_SESSION['nickname'];
        header("Location:$BASE_URL/profile.php?nickname=$nickname");
    }else{
        header("Location:$BASE_URL/editblog.php?error=1");
    }
?>