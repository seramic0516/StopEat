<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button class="login-button"type="submit">submit</button>
        </form>
        <button class="register-button" onclick="location.href='register.php'">Sign-up page</button>
    </div>
</body>
</html>


<?php
session_start();

$db = mysqli_connect('localhost', 'root', '') or die('Unable to connect. Check your connection parameters.');

$query = 'CREATE DATABASE IF NOT EXISTS diet';
mysqli_query($db, $query) or die(mysqli_error($db));

mysqli_select_db($db, 'diet') or die(mysqli_error($db));

if (isset($_POST['username'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = $_POST['password'];

    // users 테이블에서 검색
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);

    // 사용자 이름과 비밀번호를 확인
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $username;
        echo "로그인 성공!";
        echo "<script>window.location.href = 'recordWeight.html';</script>"; // 로그인 성공 시 이동할 페이지
        exit;
    } else {
        echo "잘못된 사용자 이름 또는 비밀번호입니다.";
    }
}
?>
