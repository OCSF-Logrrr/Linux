<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="../css/index.css" rel="stylesheet" type="text/css">
    <title>게시판</title>
    <h1>게시판</h1>
    <?php
        $conn=mysqli_connect("localhost","keshu","1234","DB");
        $sql="select * from member";
        $result=mysqli_query($conn,$sql);
        $num=mysqli_num_rows($result);
    ?>
</head>
<body>
<?php
    session_start();
    if(isset($_SESSION['userid']) && $_COOKIE['userid'] === 'admin'){
?>
    <div class="member">
        관리자 계정
    </div>
<?php
    }
    else{
?>
    <script>
    alert('관리자 권한이 아닙니다.');
    location="../index.php";
    </script>
    exit()
<?php
    }
?>
    <div class="search_block">
        <form action="/admin/search.php" method="get">
        사용자 검색 :
        <input type="text" name="search" required="required">
        <input type="submit" value="검색" class="button3">
        </form>
    </div>
    <div>
        <table class="list">
        <thead>
            <tr>
            <th>idx</th>
            <th>userid</th>
            <th>username</th>
            <th>삭제</th>
            </tr>
        </thead>
        <?php
            for($i=1;$i<=$num;$i++){
            $row=mysqli_fetch_object($result);
        ?>
        <tbody>
        <tr>
            <td style="width: 20%;"><?=$row->idx?></td>
            <td style="width: 60%;"><?=$row->userid?></td>
            <td style="width: 10%;"><?=$row->username?></td>
            <td style="width: 10%;"><a href="/admin/delete_user.php?idx=<?=$row->idx?>">삭제</a></td>
        </tr>
        <?php
            }
            mysqli_close($conn);
        ?>
        </tbody>
        </table>
    </div>
    <a href="../index.php"><button type="button" class="button4">홈으로</button></a>
</body>
</html>
