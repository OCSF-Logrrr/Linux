<?php
session_start();
$conn = mysqli_connect("localhost","webuser","1234","db_board");             
$URL = './index.php';
$number = $_GET['number'];
$sql ="SELECT id FROM board WHERE number='$number'";
$result = mysqli_query($conn,$sql); 
$rows = mysqli_fetch_assoc($result);
$userid = $rows['id'];


?>
<?php
if(!isset($_SESSION['userid'])) {
    ?> <script>
        alert('권한이 없습니다.');
        location.replace('<?php echo $URL?>');
        </script>
    <?php } else if ($_SESSION['userid'] == $userid) {
        $sql1 = "DELETE FROM board WHERE number='$number'";
        $result1 = mysqli_query($conn,$sql1);
        ?> <script>
            alert('삭제되었습니다.');
            location.replace('<?php echo $URL?>');
            </script>
        <?php } else { ?>
            <script>
                alert('권한이 없습니다..?');
                location.replace('<?php echo $URL?>');
            </script>
        <?php }
?>
