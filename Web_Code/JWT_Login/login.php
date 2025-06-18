<?php
$conn=mysqli_connect("localhost","test","1234","DB");
require 'JWT_class.php';

$jwt = new JWT();

$name = $_POST['name'];
$pw = $_POST['pw'];
$sql="SELECT * FROM member where userid='$name' and userpw='$pw'";
$result=mysqli_query($conn,$sql);
$row=mysqli_fetch_object($result);

    if ($row) {
        $payload = [
            'name' => $name,
        ];

        $token = $jwt->hashing($payload);

        setcookie("token", $token, time()+86400, "/", "", false, true);

        echo "<script>alert('로그인 성공');";
        echo "location='profile.php';</script>";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="/login.css" rel="stylesheet" type="text/css">
    <title>Login</title>
    <h1>로그인 페이지</h1>
</head>
<body>
    <table align="center" class="list">
        <form method="post" action="login.php">
            <tr><td><input class="member" type="text" name="name" placeholder="ID" required></td></tr>
            <tr><td><input class="member" type="text" name="pw" placeholder="Password" required></td></tr>
            <tr><td align="center"><input type="submit" value="로그인" class="button">
        </form>
    </table>
</body>
</html>
