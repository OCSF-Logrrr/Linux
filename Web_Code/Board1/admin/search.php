<!DOCTYPE html>
<html>
<?php
    $conn = mysqli_connect("localhost", "test", "1234", "DB");
    $search_con = isset($_GET['search']) ? $_GET['search'] : '';
?>
<head>
    <meta charset="utf-8">
    <link href="../css/index.css" rel="stylesheet" type="text/css">
    <title>게시판</title>
    <h1>게시판</h1>
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
<?php
    if (!empty($search_con)) {
        $sql = "SELECT * FROM member WHERE userid LIKE '%$search_con%'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        
        if ($num > 0) {
?>
            <h3><?php echo $search_con; ?>의 검색 결과</h3>
            <a href="/admin/admin.php"><button class="button4">뒤로가기</button></a>
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
            <td style="width: 60%;"><<?=$row->userid?></td>
            <td style="width: 10%;"><?=$row->username?></td>
            <td style="width: 10%;">
            <td style="width: 10%;"><a href="/admin/delete_user.php?idx=<?=$row->idx?>">삭제</a></td>
        </tr>
        <?php
            }
        ?>
        </tbody>
        </table>
<?php
       } else {
?>
            <h3><?php echo $search_con; ?>의 검색 결과가 없습니다.</h3>
            <a href="/admin/admin.php"><button class="button4">뒤로가기</button></a>
<?php
        } }
        mysqli_close($conn); 
?>
</body>
</html>
