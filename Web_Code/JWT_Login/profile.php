<link href="/css/profile.css" rel="stylesheet" type="text/css">
<?php
$conn=mysqli_connect("localhost","test","1234","DB");
$sql="select * from member";
$result=mysqli_query($conn,$sql);
$num=mysqli_num_rows($result);
require './JWT_class.php';

$jwt = new JWT();

if (!isset($_COOKIE['token'])) {
    echo "<script>alert('로그인 후 이용 가능');";
    echo "location='./login.php';</script>";
    exit;
}

$token = $_COOKIE['token'];

if ($jwt->dehashing($token)) {
    list($header, $payload, $signature) = explode('.', $token);
    $payload = json_decode(base64_decode($payload),true);
    if (($payload['name'])=='admin'){
?>
    <h1>관리자 정보 및 회원 정보</h1>
    <h3>name: admin</h3>
        <div>
        <table class="list" align="center" border="1">
        <thead>
            <tr>
            <th>username</th>
            <th>password</th>
            </tr>
        </thead>
        <?php
            for($i=1;$i<=$num;$i++){
            $row=mysqli_fetch_object($result);
        ?>
        <tbody>
        <tr> 
            <td style="width: 10%;"><?=$row->userid?></td>
            <td style="width: 10%;"><?=$row->userpw?></td>
        </tr>
        <?php
            }mysqli_close($conn);
    } else{
?>
        <h1>사용자 정보</h1>
        <h3>name: <?= $payload['name']; ?></h3>
        <div class='logout'><a href='./login.php'><button>로그아웃</button></a></div>
<?php    
    }
}
?>
