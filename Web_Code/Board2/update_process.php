<?php
$conn = mysqli_connect("localhost","webuser","1234","db_board");                
$title = $_POST['title'];
$content = $_POST['content'];
$number = $_POST['number'];


$sql ="UPDATE board SET title='$title', content='$content', date=NOW() WHERE number='$number'";

$result = mysqli_query($conn,$sql); 

if($result){
    ?> <script>
        alert("<?php echo "수정 완료되었습니다."?>");
        location.replace("./select.php?number=<?=$number?>");
        </script>
<?php
} else {
    echo "문제가 생겼습니다. 다시 시도해 주세요.";
}
?>
