<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="/css/index.css" rel="stylesheet" type="text/css">
    <title>게시판</title>
    <h1>게시판</h1>
    <?php
        $conn=mysqli_connect("localhost","test","1234","DB");
        $sql="select * from board";
        $result=mysqli_query($conn,$sql);
        $num=mysqli_num_rows($result);
    ?>
    <script>
        function check()
        {
        if(document.cookie.indexOf("userid")!=-1)
          location="board/write.php";
        else
          alert('로그인을 하세요.');
        }
    </script>
</head>
<body>
<?php
    session_start();
    if(isset($_SESSION['userid'])){
?>
    <div class="member">
        <?php echo $_COOKIE['userid'];?>님 &emsp;
        <button type="button" class="button1" onclick="location.href='/member/logout.php'">로그아웃</button>
    </div>
<?php
    }
    else{
?>
    <div class="member">
        <button type="button" class="button1" onclick="location.href='/member/login.php'">로그인</button>
        <button type="button" class="button2" onclick="location.href='/member/member.php'">회원가입</button>
    </div>
<?php
    }
?>
    <div class="search_block">
        <form action="/board/search.php" method="get">
        게시글 검색 :
        <input type="text" name="search" required="required">
        <input type="submit" value="검색" class="button3">
        </form>
    </div>
    <div>
        <table class="list">
        <thead>
            <tr>
            <th>이름</th>
            <th>제목</th>
            <th>날짜</th>
            <th>조회수</th>
            </tr>
        </thead>
        <?php
            for($i=1;$i<=$num;$i++){
            $row=mysqli_fetch_object($result);
        ?>
        <tbody>
        <script>
            function check2(idx)
            {
            if(document.cookie.indexOf("userid")!=-1)
                location="/board/content.php?idx="+idx;
            else
                alert('로그인 시 게시판 이용 가능.');
            }
        </script>
        <tr> 
            <td style="width: 15%;"><?=$row->userid?></td>
            <td style="width: 60%;"><a href="javascript:check2(<?=$row->idx?>)"><?=$row->title?></a></td>
            <td style="width: 10%;"><?=$row->date?></td>
            <td style="width: 10%;"><?=$row->hit?></td>
        </tr>
        <?php
            }
            mysqli_close($conn);
        ?>
        </tbody>
        </table>
    </div>
    <a href="javascript:check()"><button type="button" class="button4">글 작성</button></a>
</body>
</html>
