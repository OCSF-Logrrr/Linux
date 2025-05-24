<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="/css/member.css" rel="stylesheet" type="text/css">
    <title>게시판-회원가입</title>
    <h1>회원가입</h1>
    <script>
        function check(){
            var userpw=document.getElementsByName('userpw')[0].value;
            var userpw_check=document.getElementsByName('userpw_check')[0].value;
            if(userpw!==userpw_check){
                alert('비밀번호가 일치하지 않습니다.');
                return false;
            }
        }
    </script>
</head>
<body> 
    <table align="center" class="list">
        <form method="POST" action="/member/member_ok.php" onsubmit="return check()">
            <tr><td><input class="member" type="text" name="userid" placeholder="ID" required></td></tr>
            <tr><td><input class="member" type="password" name="userpw" placeholder="Password" required></td></tr>
            <tr><td><input class="member" type="password" name="userpw_check" placeholder="Password Check" required></td></tr>
            <tr><td><input class="member" type="text" name="username" placeholder="Name"></td></tr>
            <tr><td><input class="member" type="text" name="userphone" placeholder="Phone Number"></td></tr>
            <tr><td>
                <div class="email">
                    <input class="member" type="text" name="email_id" placeholder="E-mail">
                    <span>@</span>
                    <select name="email_domain" class="member">
                        <option value="naver.com">naver.com</option>
                        <option value="gmail.com">gmail.com</option>
                    </select>
                </div>
            </td></tr>
            <tr><td align="center"><input type="submit" value="회원가입" class="button">
        </form>
            <a href="../index.php"><button class="button">홈으로</button></a></td></tr>
    </table>
</body>
</html>
