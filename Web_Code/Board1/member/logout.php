<?php
    session_start();
    session_unset();
    setcookie('userid',' ',time()-86400,"/");
    echo "<script>alert(\"로그아웃 되었습니다.\");location.href=\"../index.php\";</script>";
?>
