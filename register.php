<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="container">
        <h1>Sign up</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button class="register-button" type="submit">submit</button>
        </form>
        <button class="login-button" onclick="location.href='login.php'">login page</button>
    </div>
</body>
</html>

<?php

$db = mysqli_connect('localhost', 'root', '') or die('Unable to connect. Check your connection parameters.');

$query = 'CREATE DATABASE IF NOT EXISTS diet';
mysqli_query($db, $query) or die(mysqli_error($db));

mysqli_select_db($db, 'diet') or die(mysqli_error($db));

// users 테이블 생성
$query = 'CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=MyISAM';
mysqli_query($db, $query) or die(mysqli_error($db));

// records 테이블 생성
$query = 'CREATE TABLE IF NOT EXISTS records (
    record_date DATE NOT NULL,
    user_id INT NOT NULL,
    total_calories INT,
    weight DECIMAL(5,2),
    PRIMARY KEY (record_date, user_id),
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=MyISAM';
mysqli_query($db, $query) or die(mysqli_error($db));

// menu_entries 테이블 생성
$query = 'CREATE TABLE IF NOT EXISTS menu_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    record_date DATE NOT NULL,
    menu VARCHAR(100),
    calories INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=MyISAM';
mysqli_query($db, $query) or die(mysqli_error($db));

// 회원가입 폼에서 데이터를 받습니다.
if (isset($_POST['username'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // 이미 존재하는지 확인.
    $check_query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($db, $check_query);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('이미 존재하는 아이디입니다!');</script>";
    } else {
        // users 테이블에 데이터를 삽입
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if (mysqli_query($db, $query)) {
            echo "<script>alert('회원가입이 성공적으로 완료되었습니다!');
            window.location.href = 'login.php';</script>";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($db);
        }
    }
}
?>
