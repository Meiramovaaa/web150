<?php
    include "../../config/base_url.php";

    session_start();
    if(isset($_SESSION['nickname'])) {
        $start = time();
        session_destroy();
    }
    header("Location:$BASE_URL/login.php");
?>