<?php
session_start();
$conn = mysqli_connect("localhost","webuser","1234","db_board");  
$id = $_POST['id'];
$pw = $_POST['pw'];     

$sql = "SELECT * FROM member WHERE id='$id'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);

    if ($row['password'] == $pw){
        $_SESSION['userid'] = $id;
        if (isset($_SESSION['userid'])) {
            ?> <script>
                location.replace('./index.php');
                </script>
            <?php
            } else {
                echo "session failed";
        }
    } else {
        ?> <script>
            alert("아이디 또는 비밀번호를 확인해주세요.");
            history.back(); //이전 페이지로 돌아가는 명령어
            </script>
        <?php
    }
} else{
    ?> <script>
        alert("아이디 또는 비밀번호를 확인해주세요.");
        history.back();
        </script>
    <?php
}
?>
