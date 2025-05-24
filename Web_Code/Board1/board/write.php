<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="/css/write.css" rel="stylesheet" type="text/css">
    <title>게시판-글 작성</title>
    <h1>글 작성</h1>
</head>
<body>
    <form method="POST" action="/board/write_ok.php" enctype="multipart/form-data">
        <table class="list">
            <tr><td><textarea class="title" name="title" placeholder="제목" required></textarea></td></tr>
            <tr><td><input type="text" name="file_name" placeholder="파일 이름 (확장자 포함)"><input class="upload" name="upload_file" type="file"></td></tr>
            <tr><td><textarea class="content" name="content" placeholder="내용을 입력하세요." required></textarea></td></tr>
        </table>
            <input type="submit" class="button" value="작성">
    </form>
</body>
</html>
