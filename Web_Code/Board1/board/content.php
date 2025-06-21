<?php
    $conn = mysqli_connect("3.39.142.255", "root", "1234", "board");

    $idx = $_GET["idx"];

    $sql = "SELECT * FROM board WHERE idx = $idx";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_object($result);

    $hit_sql = "UPDATE board SET hit = hit + 1 WHERE idx = '$idx'";
    mysqli_query($conn, $hit_sql);

    $file_name = $row->file_name;
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $is_image = in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);

    $upload_path = "/webapi/files/";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
        <base href="/webapi/">
    <link href="/css/content.css" rel="stylesheet" type="text/css">
    <title>게시판 - <?=htmlspecialchars($row->userid)?>님의 글</title>
</head>
<body>
    <h1><?=htmlspecialchars($row->userid)?>님의 글</h1>
    <table class="list">
        <tr><td class="title"><?=htmlspecialchars($row->title)?></td></tr>
        <tr><td class="content"><?=nl2br(htmlspecialchars($row->content))?></td></tr>
        <?php if (!empty($file_name)): ?>
        <tr>
            <td class="file">
                업로드 된 파일 :
                <a href="/webapi/board/download.php?file=<?=urlencode($file_name)?>" download>
                    <?=htmlspecialchars($file_name)?>
                </a>
            </td>
        </tr>
        <?php if ($is_image): ?>
        <tr>
            <td class="image-preview">
                <p>미리보기:</p>
                <img src="<?= $upload_path . urlencode($file_name) ?>" alt="이미지 미리보기" style="max-width: 500px; max-height: 400px;">
            </td>
        </tr>
        <?php endif; ?>
        <?php endif; ?>
    </table>
    <div class="button-container">
        <a href="../../index.php"><button type="button" class="button">홈으로</button></a>
        <form method="post" action="/webapi/board/delete.php">
            <input type="hidden" name="idx" value="<?=$row->idx?>">
            <input type="submit" value="삭제" class="button2">
        </form>
    </div>
</body>
</html>
