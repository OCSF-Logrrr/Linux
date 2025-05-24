<?php
session_start();
$result = session_destroy();

if($result) { //성공시엔 true, 실패 시 false
    ?><script>
        history.back();
        </script>
    <?php } ?>
