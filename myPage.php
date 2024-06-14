<?php
session_start();

$db = mysqli_connect('localhost', 'root', '') or die('Unable to connect. Check your connection parameters.');

mysqli_select_db($db, 'diet') or die(mysqli_error($db));

if (!isset($_SESSION['username'])) {
    echo "<script>alert('로그인이 필요합니다.'); window.location.href='login.php';</script>";
    exit;
}

$username = $_SESSION['username'];

if (isset($_POST['newPassword'])) {
    $query = "SELECT id, password FROM users WHERE username = '$username'";
    $result = mysqli_query($db, $query) or die(mysqli_error($db));
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['id'];
    $currentPasswordHash = $user['password'];

    if (isset($_POST['currentPassword'], $_POST['newPassword'])) {
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];

        // 현재 비밀번호 확인
        if (password_verify($currentPassword, $currentPasswordHash)) {
            $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);

            // 새로운 비밀번호를 업데이트
            $query = 
            "UPDATE users 
                SET password = '$newPasswordHash' 
                WHERE id = '$user_id'";
            mysqli_query($db, $query) or die(mysqli_error($db));

            echo "<script>alert('비밀번호가 변경되었습니다!'); window.location.href='myPage.php';</script>";
        } else {
            echo "<script>alert('현재 비밀번호가 일치하지 않습니다.'); window.location.href='myPage.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Page</title>
    <link rel="stylesheet" href="myPage.css?after">
</head>
<body>
    <header>
        <img id="logo" src="logo.png" alt="로고"/>
        <nav class="nav-links">
            <a href="recordWeight.html">몸무게 기록</a>
            <a href="recordMeal.html">식단 기록</a>
            <a href="viewMeal.php">식단 조회</a>
        </nav>
        <div class="header-buttons">
            <button class="mypage" onclick="location.href='myPage.php'">My page</button>
            <button class="logout" onclick="location.href='logout.php'">Logout</button>
        </div>
    </header>
    <div class="container">
        <div class="header">
            <h2><?php echo $username; ?>님의 MyPage입니다</h2>
        </div>
        <h2>비밀번호 변경</h2>
        <form action="myPage.php" method="post">
            <label for="currentPassword">현재 비밀번호:</label>
            <input type="password" id="currentPassword" name="currentPassword" required>

            <label for="newPassword">새 비밀번호:</label>
            <input type="password" id="newPassword" name="newPassword" required>

            <input type="submit" value="비밀번호 변경">
        </form>
    </div>
</body>
</html>
