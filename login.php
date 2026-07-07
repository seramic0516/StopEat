<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StopEat | 로그인</title>
    <link rel="icon" href="logo.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="common.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="auth-page">
        <div class="container">
            <img src="logo.png" alt="StopEat 로고" class="auth-logo">
            <h1>다시 만나서 반가워요!</h1>
            <p class="auth-subtitle">오늘의 식단과 몸무게를 기록해볼까요?</p>
            <form action="" method="post">
                <div class="form-group">
                    <label for="username">아이디</label>
                    <input type="text" id="username" name="username" placeholder="아이디를 입력하세요" required>
                </div>
                <div class="form-group">
                    <label for="password">비밀번호</label>
                    <input type="password" id="password" name="password" placeholder="비밀번호를 입력하세요" required>
                </div>
                <button class="login-button btn" type="submit">로그인</button>
            </form>
            <div class="auth-divider"><span>아직 계정이 없다면</span></div>
            <button class="register-button btn btn-outline" onclick="location.href='register.php'">회원가입 하러 가기</button>
        </div>
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
