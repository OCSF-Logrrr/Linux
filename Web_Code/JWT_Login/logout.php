<?php
setcookie("token", "", time()-86400, "/");
echo "location='./login.php'";
?>
