<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="/css/member.css" rel="stylesheet" type="text/css">
    <title>게시판-로그인</title>
    <h1>로그인</h1>
</head>
<body> 
<?php
    session_start();
    if(isset($_SESSION['userid'])){
        echo "<script>alert(\"이미 로그인 하셨습니다.\");
        location.href=\"../index.php\"</script>";
    }
    else{
?>
    <table align="center" class="list_login">
        <form method="POST" action="/member/login_ok.php">
            <tr><td><input class="member" type="text" name="userid" placeholder="ID" required></td></tr>
            <tr><td><input class="member" type="text" name="userpw" placeholder="Password" required></td></tr>
            <tr><td align="center"><input type="submit" value="로그인" class="button">
        </form>
            <a href="../index.php"><button class="button">홈으로</button></a></td></tr>
    </table>
</body>
</html>
<?php
    }
?>
