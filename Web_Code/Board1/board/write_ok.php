<?php
    $conn=mysqli_connect("localhost","keshu","1234","DB");

    $title=$_POST['title'];
    $content=$_POST['content'];
    $cookie=$_COOKIE['userid'];
    $date=date('Y-m-d');
    
    if($_FILES['upload_file']!=NULL){
        $file_name=$_POST['file_name'];
        $upload_file=$_FILES['upload_file'];
        $file_path="../files/".$file_name;
        move_uploaded_file($upload_file['tmp_name'],$file_path);
    }

    $sql="INSERT INTO board(userid,title,content,date,hit,file_name) VALUES('$cookie','$title','$content','$date',0,'$file_name')";
    $result=mysqli_query($conn,$sql);

    header("location:../index.php");

    mysqli_close($conn);
?>
