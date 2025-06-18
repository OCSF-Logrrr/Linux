<?php
setcookie("token", "", time()-86400, "/");
echo "location='/JWT_Login/login.php'";
?>
