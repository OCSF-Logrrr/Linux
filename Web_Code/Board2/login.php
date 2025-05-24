<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div align="center">
        <span>
            <p style="font-size:25px;"><b>로그인</b></p>
        </span>

        <form action="login_process.php" method="post">
            <p><b>I &nbsp;D &nbsp;</b><input name="id" type="text"></p>
            <p><b>PW &nbsp;</b><input name="pw" type="password"></p>
            <br />&nbsp;
            <input type="submit" value="로그인">&nbsp;&nbsp;
        </form><br />
        <button id="register" onclick="location.href='./register.php'">회원가입</button>
    </div>
</body>
</html>
