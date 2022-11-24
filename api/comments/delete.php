<?php
    include "../../config/db.php";
    if(isset($_GET['id']) && intval($_GET['id'])){
        $prep = mysqli_prepare($con, 
        "DELETE FROM comments WHERE id=?");
        mysqli_stmt_bind_param($prep, "i", $_GET['id']);
        mysqli_stmt_execute($prep);
    }
?>