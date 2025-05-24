<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        .select_table {
            border: 1px solid #444444;
            margin-top: 30px;
        }

        .select_title {
            height: 45px;
            font-size: 23.5px;
            text-align: center;
            background-color: #5C5C5C5C;
            color: white;
            width: 1000px;
        }

        .select_id {
            text-align: center;
            background-color: #EEEEEE;
            width: 30px;
            height: 33px;
        }

        .select_id2 {
            background-color: white;
            width: 60px;
            height: 33px;
            padding-left: 10px;
        }

        .select_hit {
            background-color: #EEEEEE;
            width: 30px;
            text-align: center;
            height: 33px;
        }

        .select_hit2 {
            background-color: white;
            width: 60px;
            height: 33px;
            padding-left: 10px;
        }

        .select_content {
            padding: 20px;
            border-top: 1px solid #444444;
            height: 500px;
        }

        .select_btn {
            width: 700px;
            height: 200px;
            text-align: center;
            margin: auto;
            margin-top: 50px;
        }

        .select_btn1 {
            height: 50px;
            width: 100px;
            font-size: 20px;
            text-align: center;
            background-color: white;
            border: 2px solid black;
            border-radius: 10px;
        }

        .select_comment_input {
            width: 700px;
            height: 500px;
            text-align: center;
            margin: auto;
        }

        .select_text3 {
            font-weight: bold;
            float: left;
            margin-left: 20px;
        }

        .select_com_id {
            width: 100px;
        }

        .select_comment {
            width: 500px;
        }
    </style>
</head>
<body>
    <?php
    $conn = mysqli_connect("localhost","webuser","1234","db_board");             
    $number = $_GET['number'];
    session_start();
    $sql = "SELECT title, content, date, hit, id FROM board WHERE number=$number";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_fetch_assoc($result);

    $hit = "UPDATE board SET hit = hit + 1 WHERE number = $number";
    mysqli_query($conn, $hit);

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


    <table class="select_table" align="center">
        <tr>
            <td colspan="4" class="select_title"><?php echo $rows['title']?></td>
        </tr>
        <tr>
            <td class="select_id">글쓴이</td>
            <td class="select_id2"><?php echo $rows['id']?></td>
            <td class="select_hit">조회수</td>
            <td class="select_hit2"><?php echo $rows['hit']?></td>
        </tr>

        <tr>
            <td colspan="4" class="select_content" vlign="top"><?php echo $rows['content']?></td>
        </tr>

    </table>
    <div class="select_btn">
        <button class="select_btn1" onclick="location.href='./index.php'">목록</button>
        <?php
        if (isset($_SESSION['userid']) and $_SESSION['userid'] == $rows['id']) { ?>
            <button class="select_btn1" onclick="location.href='./update.php?number=<?= $number ?>'">수정</button>
            <button class="select_btn1" onclick="location.href='./delete_process.php?number=<?= $number ?>&id=<?= $_SESSION['userid'] ?>'">삭제</button>
        <?php } ?>
    </div>
    
</body>
</html>
