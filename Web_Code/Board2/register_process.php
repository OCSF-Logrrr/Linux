<?php
$conn = mysqli_connect("localhost","webuser","1234","db_board");  
$id = $_POST['id'];
$pw = $_POST['pw'];     


//id 중복 확인용
$sql1 = "SELECT * FROM member WHERE id='$id'";
$result1 = mysqli_query($conn,$sql1);
$count = mysqli_num_rows($result1);

if($count){ //중복 id가 존재한다면
?> <script>
    alert('이미 존재하는 ID입니다.');
    history.back();
    </script>
    <?php } else { //없다면
    $sql = "INSERT INTO member 
            (id, password, date, permit)
            VALUES('$id','$pw',NOW(),0)
            
            ";

    $result = mysqli_query($conn,$sql);
    //저장되었다면 result=true 가입 완료
    if($result){
        ?> <script>
            alert('회원가입 되었습니다.');
            location.replace('./login.php');
            </script>
        <?php } else { 
            ?> <script>
            alert('회원가입에 실패하였습니다.');
            </script>
            <?php }
        }
mysqli_close($conn);

?>
