<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        table.table2 {
            border-collapse: separate;
            border-spacing: 1px;
            text-align: left;
            line-height: 1.5;
            border-top: 1px solid #ccc;
            margin: 20px 10px;
        }
        table.table2 tr{
            width: 50px;
            padding: 10px;
            font-weight: bold;
            vertical-align: top;
            border-bottom: 1px solid #ccc;
        }
        table.table2 td{
            width: 100px;
            padding: 10px;
            vertical-align: top;
            border-bottom: 1px solid #ccc;
        }

    </style>
</head>
<body>
<?php
    $conn = mysqli_connect("localhost","webuser","1234","db_board");             
    $number = $_GET['number'];
    $sql = "SELECT title, content, date, hit, id FROM board WHERE number=$number";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_fetch_assoc($result);

    $title =$rows['title'];
    $content = $rows['content'];
    $userid = $rows['id'];

    session_start();
    $URL = './index.php';

    if(!isset($_SESSION['userid'])){
        ?> <script>
            alert('권한이 없습니다.');
            location.replace('<?php echo $URL?>');
            </script>
        <?php } else if ($_SESSION['userid'] == $userid) {
            ?>
            <form action="update_process.php" method="post">
        <table style="padding-top:50px" align=center width=auto border=0 cellpadding=2>
            <tr>
                <td style="height:40; float:center; background-color:#5C5C5C">
                    <p style="font-size:25px; text-align:center; color:whilte; margin-top:15px; margin-bottom:15px"><b>게시글 수정하기</b></p>
                </td>
            </tr>
            <tr>
                <td bgcolor=white>
                    <table class="table2">
                        <tr>
                            <td>글쓴이</td>
                            <td><input type="hidden" name="name" value="<?=$_SESSION['userid']?>" size=30><?=$_SESSION['userid']?></td>
                        </tr>
                        <tr>
                            <td>제목</td>
                            <td><input type="text" name="title" size=70 value="<?=$title?>"></td>
                        </tr>
                        <tr>
                            <td>내용</td>
                            <td><textarea name="content" cols=75 rows=15><?=$content?></textarea></td>
                        </tr>
                        <!-- 비밀번호 입력란 X -->
                    </table>
                    <center>
                        <input type="hidden" name="number" value="<?=$number?>">
                        <input style="height:26px; width:47px; font-size:16px;" type="submit" value="수정">
                    </center>

                </td>
            </tr>
        </table>
    </form>
    <?php  } else {
        ?> <script>
            alert('권한이 없습니다.');
            location.replace('<?php echo $URL?>');
        </script>
        <?php }
    ?>
</body>
</html>
