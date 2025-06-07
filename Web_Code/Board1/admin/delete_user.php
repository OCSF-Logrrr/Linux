<?php
    $conn=mysqli_connect("localhost","test","1234","DB");
    $idx=$_GET["idx"];
    $userid=$_COOKIE['userid'];
    if ($_COOKIE['userid'] !== 'admin') {
        echo "<script>alert('관리자가 아닙니다.'); location.href='../index.php';</script>";
        exit();
    }

    $sql = "SELECT count(*) as cnt FROM member WHERE idx='$idx'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_object($result);
    if($row->cnt==0){
?>
        <script>
        alert("계정이 존재하지 않습니다.");
        location.href="../index.php";
        </script>
<?php
        exit();
    }
    else{
?>
<?php 
        $sql2="delete from member where idx=$idx";
        mysqli_query($conn,$sql2);
    }
    mysqli_close($conn);
    header("location:/admin/admin.php");
?>
