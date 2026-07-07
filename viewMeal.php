<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StopEat | 식단 조회</title>
    <link rel="icon" href="logo.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="common.css">
    <link rel="stylesheet" href="viewMeal.css">
</head>
<body>
    <header>
        <img id="logo" src="logo.png" alt="로고"/>
        <nav class="nav-links">
            <a href="recordWeight.html">몸무게 기록</a>
            <a href="recordMeal.html">식단 기록</a>
            <a href="viewMeal.php" class="active">식단 조회</a>
        </nav>
        <div class="header-buttons">
            <button class="mypage" onclick="location.href='myPage.php'">My page</button>
            <button class="logout" onclick="location.href='logout.php'">Logout</button>
        </div>
    </header>

    <div class="page-heading">
        <h1>식단 조회</h1>
        <p>기록해둔 날짜를 선택하면 그날의 식단을 볼 수 있어요</p>
    </div>

    <form id="view-form" method="post">
        <label for="date">날짜 선택</label>
        <input type="date" id="date" name="date">
        <button type="submit" name="submit" value="Submit" id="view" class="btn">조회하기</button>
    </form>

    <?php
    session_start();

    if (!isset($_SESSION['username'])) {
        echo "<script>alert('로그인이 필요합니다.'); window.location.href = 'login.php';</script>";
        exit();
    }

    $username = $_SESSION['username'];

    $db = mysqli_connect('localhost', 'root', '', 'diet') or die('Unable to connect. Check your connection parameters.');

    // 사용자의 ID를 가져옵니다.
    $userQuery = "SELECT id 
                    FROM users 
                    WHERE username = '$username'";
    $userResult = mysqli_query($db, $userQuery) or die(mysqli_error($db));
    $user = mysqli_fetch_assoc($userResult);
    $user_id = $user['id'];

    // 'comments' 테이블을 생성합니다.
    $query = 'CREATE TABLE IF NOT EXISTS comments (
        comment_id INT AUTO_INCREMENT PRIMARY KEY,
        record_date DATE NOT NULL,
        comment TEXT NOT NULL,
        comment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=MyISAM';
    mysqli_query($db, $query) or die(mysqli_error($db));

    // 특정 날짜의 기록을 가져오는 코드
    if (isset($_POST['date'])) {
        $date = $_POST['date'];

        // 총 칼로리 가져오기
        $query = "SELECT total_calories 
                    FROM records 
                    WHERE record_date = '$date' AND user_id = '$user_id'";
        $result = mysqli_query($db, $query) or die(mysqli_error($db));
        $record = mysqli_fetch_assoc($result);
        $totalCalories = $record ? $record['total_calories'] : 0;

        // 메뉴 항목 가져오기 (0 칼로리 제외)
        $query = "SELECT menu, calories
                    FROM menu_entries
                    WHERE record_date = '$date' AND user_id = '$user_id' AND calories > 0";
        $result = mysqli_query($db, $query) or die(mysqli_error($db));

        echo "<div class='result-card'>";
        echo "<div class='result-summary'>";
        echo "<div class='result-date'>" . htmlspecialchars($date) . "</div>";
        echo "<div class='result-total'><span>" . (int)$totalCalories . "</span> kcal</div>";
        echo "</div>";

        echo "<h3 class='result-subtitle'>메뉴 항목</h3>";
        echo "<ul class='menu-list'>";
        $hasMenu = false;
        while ($row = mysqli_fetch_assoc($result)) {
            $hasMenu = true;
            echo "<li><span class='menu-name'>" . htmlspecialchars($row['menu']) . "</span><span class='menu-calorie'>" . (int)$row['calories'] . " kcal</span></li>";
        }
        if (!$hasMenu) {
            echo "<li class='empty-state'>등록된 메뉴가 없어요.</li>";
        }
        echo "</ul>";
        echo "</div>";
    }

    // 댓글 작성
    if (isset($_POST['comment'])) {
        $comment = $_POST['comment'];
        $query = "INSERT 
                    INTO comments (record_date, comment) 
                    VALUES (CURDATE(), '$comment')";
        mysqli_query($db, $query) or die(mysqli_error($db));
        echo "<script>window.location.href = 'viewMeal.php';</script>";
        exit();
    }
    ?>

    <!-- 댓글 입력 폼 -->
    <div class="comment-section">
        <h3>댓글 남기기</h3>
        <form id="comment-form" method="post">
            <textarea id="comment" name="comment" rows="3" placeholder="오늘의 다짐이나 소감을 남겨보세요."></textarea>
            <button type="submit" id="commentButton" class="btn">댓글 작성</button>
        </form>
    </div>

    <!-- 모든 댓글 display -->
    <div class="comment-section">
        <h3>모든 댓글</h3>
        <?php
        $query = "SELECT comment, comment_date
                    FROM comments
                    ORDER BY comment_date DESC";
        $result = mysqli_query($db, $query) or die(mysqli_error($db));

        echo "<div class='comment-list'>";
        $hasComment = false;
        while ($row = mysqli_fetch_assoc($result)) {
            $hasComment = true;
            echo "<div class='comment-item'>";
            echo "<p>" . nl2br(htmlspecialchars($row['comment'])) . "</p>";
            echo "<small>" . htmlspecialchars($row['comment_date']) . "</small>";
            echo "</div>";
        }
        if (!$hasComment) {
            echo "<p class='empty-state'>아직 작성된 댓글이 없어요.</p>";
        }
        echo "</div>";
        ?>
    </div>
</body>
</html>
