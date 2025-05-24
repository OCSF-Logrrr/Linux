<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        table {
            border-top: 1px solid #444444;
            border-collapse: collapse;
        }
        
        tr {
            border-bottom: 1px solid #444444;
            padding: 10px;
        }

        table .even {
            background: #efefef;
        }

        .text {
            text-align: center;
            padding-top: 20px;
            color: #000000
        }

        .text:hover {
            text-decoration: underline;
        }

        a:link {
            color: #57A0EE;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>
  <?php
    $conn = mysqli_connect("localhost","webuser","1234","db_board");
    $sql = "SELECT * FROM board ORDER BY NUMBER DESC"; 
    $result = mysqli_query($conn, $sql);
    $total = mysqli_num_rows($result); 

    session_start();

    if (isset($_SESSION['userid'])) {
        ?><b><?php echo $_SESSION['userid']; ?></b>님 반갑습니다.
        <button onclick="location.href='./logout_process.php'" style="float:right; font-size:15.5px;">로그아웃</button>
    <br />
    <?php
    } else {
    ?>
        <button onclick="location.href='./login.php'" style="float:right; font-size:15.5px;">로그인</button>
        <br />
    <?php
    }
    ?>
    <?php
    $conn = mysqli_connect("localhost", "webuser", "1234", "db_board");
    $sql = "SELECT * FROM board ORDER BY NUMBER DESC"; //역순 출력
    $result = mysqli_query($conn, $sql);
    $total = mysqli_num_rows($result); //result set의 총 행 수 반환
    ?>
    <p style = "font-size:25px; text-align:center"><b>게시판</b></p>
    <table align=center>
        <thead align="center">
            <tr>
                <td width="50" align="center">번호</td>
                <td width = "500" align="center">제목</td>
                <td width = "100" align="center">작성자</td>
                <td width = "200" align="center">날짜</td>
                <td width = "50" align="center">조회수</td>

            </tr>
        </thead>
        <tbody>
            <?php
            while($rows = mysqli_fetch_assoc($result)) { // result set에서 행을 1개씩 리턴시킨다
                if ($total % 2 == 0){
                    ?>
                    <tr class="even">
                    <?php } else {
                    ?>
                    <tr>
                    <?php } ?>
                    <td width="50" align="center"><?php echo $total ?></td>
                    <td width="500" align="center">
                        <a href="select.php?number=<?php echo $rows['number']?>">
                        <?php echo $rows['title']?>
                    </td>
                    <td width="100" align="center"><?php echo $rows['id'] ?></td>
                    <td width="200" align="center"><?php echo $rows['date'] ?></td>
                    <td width="50" align="center"><?php echo $rows['hit'] ?></td>
                    </tr>
                <?php
                $total--;    
            }
                ?>
        </tbody>
    </table>
    <div class=text>
        <font style="cursor: hand" onClick="location.href='./create.php'">글쓰기</font>
    </div>
</body>
</html>
