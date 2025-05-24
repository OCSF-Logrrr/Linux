<?php
$conn = mysqli_connect("localhost","webuser","1234","db_board");             
$URL = './index.php';         

$sql ="INSERT INTO board
        (title, content, date, hit, id, password) 
        values(
        '{$_POST['title']}',
        '{$_POST['content']}',
        NOW(),
         0,
        '{$_POST['name']}',
        '{$_POST['password']}'
        )";
$result = mysqli_query($conn,$sql); 

if($result === false){
    echo "문제가 생겼습니다. 다시 시도해 주세요.";
    
} else{
    echo "게시글이 등록되었습니다. <a href='$URL'>돌아가기</a>";
}

?>
