<?php
    $conn=mysqli_connect("localhost","keshu","1234","DB");
    $idx=$_POST["idx"];
    $userid=$_COOKIE['userid'];
    $sql="SELECT count(*) as cnt FROM board where idx='$idx' and userid='$userid'";
    $result=mysqli_query($conn,$sql);
    $row=mysqli_fetch_object($result);
    if($row->cnt==0){
?>
        <script>
        alert("자신이 작성한 글만 삭제 가능");
        location.href="../index.php";
        </script>
<?php
    exit();
    }
    else{
        $sql2="delete from board where idx=$idx";
        mysqli_query($conn,$sql2);
        $sql3="delete from comments where idx=$idx";
        mysqli_query($conn,$sql3);
    }
    mysqli_close($conn);
    header("location:../index.php");
?>
