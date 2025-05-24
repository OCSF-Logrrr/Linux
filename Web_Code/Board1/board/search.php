<!DOCTYPE html>
<html>
<?php
    $conn = mysqli_connect("localhost", "test", "1234", "DB");
    $search_con = isset($_GET['search']) ? $_GET['search'] : '';
?>
<head>
    <meta charset="utf-8">
    <link href="/css/index.css" rel="stylesheet" type="text/css">
    <title>게시판</title>
    <h1>게시판</h1>
</head>
<body>
<?php
    if (!empty($search_con)) {
        $sql = "SELECT * FROM board WHERE title LIKE '%$search_con%'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        
        if ($num > 0) {
            //$search_con=str_ireplace('>','&gt;', $search_con); //엔티티로 치환
            //$search_con=str_ireplace('<','&lt;', $search_con); //엔티티로 치환
            //$search_con=htmlspecialchars($_GET['search']); //엔티티로 출력
?>
            <h3><?php echo $search_con; ?>의 검색 결과</h3>
            <a href="../index.php"><button class="button4">홈으로</button></a>
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
            function check2()
            {
            if(document.cookie.indexOf("userid")!=-1)
                location="/board/content.php?idx=<?=$row->idx?>";
            else
                alert('로그인을 하세요.');
            }
        </script>
        <tr> 
            <td style="width: 20%;"><?=$row->userid?></td>
            <td style="width: 60%;"><a href="javascript:check2()"><?=$row->title?></a></td>
            <td style="width: 10%;"><?=$row->date?></td>
            <td style="width: 10%;"><?=$row->hit?></td>
        </tr>
        <?php
            }
        ?>
        </tbody>
        </table>
<?php
       } else {
        //$search_con=str_ireplace('>','&gt;', $search_con); //엔티티로 치환
        //$search_con=str_ireplace('<','&lt;', $search_con); //엔티티로 치환
        //$search_con=htmlspecialchars($_GET['search']); //엔티티로 출력
?>
            <h3><?php echo $search_con; ?>의 검색 결과가 없습니다.</h3>
            <a href="../index.php"><button class="button4">홈으로</button></a>
<?php
        } }
        mysqli_close($conn); 
?>
</body>
</html>
