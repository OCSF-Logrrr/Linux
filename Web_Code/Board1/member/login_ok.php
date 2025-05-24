<?php
    $conn=mysqli_connect("localhost","keshu","1234","DB");

    $userid=$_POST['userid'];
    $userpw=$_POST['userpw'];

    $sql="SELECT * FROM member where userid='$userid' and userpw='$userpw'";
    $result=mysqli_query($conn,$sql);
    $row=mysqli_fetch_object($result);

    if(!$row){
?>
        <script>
            alert("로그인 정보가 없습니다.");
            location="/member/login.php";
        </script>
<?php
    }
    else{
        setcookie("userid",$row->userid,time()+86400,"/");
?>
        <script>
            alert("로그인 성공!");
        </script>
<?php
        session_start();
        $_SESSION['userid']=$row=['userid']; //userid라는 세션 변수 생성 후 로그인된 userid를 저장
        header("location:../index.php");
    }
    mysqli_close($conn);
?>
