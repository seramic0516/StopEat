<?php
session_start();

$db = mysqli_connect('localhost', 'root', '') or die('Unable to connect. Check your connection parameters.');

$query = 'CREATE DATABASE IF NOT EXISTS diet';
mysqli_query($db, $query) or die(mysqli_error($db));

mysqli_select_db($db, 'diet') or die(mysqli_error($db));

$response = [];

// 특정 날짜의 일주일 간의 몸무게 기록을 가져오기
if (isset($_GET['start_date']) && isset($_GET['end_date']) && isset($_SESSION['username'])) {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $username = $_SESSION['username'];

    // 사용자의 ID
    $userQuery = "SELECT id FROM users WHERE username = '$username'";
    $userResult = mysqli_query($db, $userQuery) or die(mysqli_error($db));
    $user = mysqli_fetch_assoc($userResult);
    $user_id = $user['id'];

    // records 테이블 user_id와 id가 같은 경우
    $query = "SELECT record_date, weight FROM records WHERE record_date BETWEEN '$start_date' AND '$end_date' AND user_id = '$user_id' ORDER BY record_date";
    $result = mysqli_query($db, $query) or die(mysqli_error($db));
    $weightRecords = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $weightRecords[] = $row;
    }
    $response['weightRecords'] = $weightRecords;
}

echo json_encode($response);
?>
