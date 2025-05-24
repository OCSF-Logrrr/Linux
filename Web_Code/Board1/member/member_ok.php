<?php
    $conn=mysqli_connect("localhost","keshu","1234","DB");

    $userid=$_POST["userid"];
    $userpw=$_POST["userpw"];
    $username=$_POST["username"];
    $userphone=$_POST["userphone"];
    $email_id = $_POST["email_id"];
    $email_domain = $_POST["email_domain"];
    $useremail = $email_id . '@' . $email_domain;

    $sql="SELECT count(*) as cnt FROM member where userid='$userid'";
    $result=mysqli_query($conn,$sql);
    $row=mysqli_fetch_object($result);

    if($row->cnt!=0){
?>
        <script>
            alert('중복된 아이디 입니다.');
            location.href="/member/member.php";
        </script>
<?php
    }
    else{
        $sql2="INSERT INTO member(userid,userpw,userphone,useremail,username) VALUES('$userid','$userpw','$userphone','$useremail','$username')";
        $result=mysqli_query($conn,$sql2);
?>
        <script>
            alert('회원가입 완료.');
            location.href="/member/member_completion.php";
        </script>
<?php
    }
    mysqli_close($conn);
?>

