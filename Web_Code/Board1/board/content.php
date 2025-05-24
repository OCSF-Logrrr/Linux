<?php
    $conn=mysqli_connect("localhost","keshu","1234","DB");
    $idx=$_GET["idx"];
    $sql="select * from board where idx=$idx";
    $result=mysqli_query($conn,$sql);
    $row=mysqli_fetch_object($result);
    $hit_sql="UPDATE board set hit=hit+1 where idx='$idx'";
    mysqli_query($conn,$hit_sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="/css/content.css" rel="stylesheet" type="text/css">
    <title>게시판-<?=$row->userid?>님의 글</title>
    <h1><?=$row->userid?>님의 글</h1>
</head>
<body>
    <table class="list">
        <tr><td class="title"><?=$row->title?></td></tr>
        <tr><td class="file">업로드 된 파일 : <a href="/board/download.php?file=<?=$row->file_name?>" download><?=$row->file_name?></a></td></tr>
        <tr><td class="content"><?=nl2br($row->content)?></td></tr>
    </table>
    <div class="button-container">
    <a href="../index.php"><button type="button" class="button">홈으로</button></a>
    <form method="post" action="/board/delete.php">
        <input type="hidden" name="idx" value="<?=$row->idx?>">
        <input type="submit" value="삭제" class="button2">
    </form>
    </div>
</body>
</html>
