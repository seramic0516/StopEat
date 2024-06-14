<?php
/* logout.php */
session_start();
session_unset();
session_destroy();
echo "<script>alert('로그아웃 완료!');window.location.href = 'login.php';</script>";
?>