<?php
$filename = $_GET['file'];

if ($filename) {
    $filepath = '../files/' . $filename;

    if (file_exists($filepath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($filepath));
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        header('X-File-Path: ' . $filepath);
        readfile($filepath);
        exit;
    } else {
        echo "파일이 존재하지 않습니다.";
    }
} else {
    echo "파일명을 입력해 주세요.";
}
?>
