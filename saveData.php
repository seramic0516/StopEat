<?php
session_start();

$db = mysqli_connect('localhost', 'root', '') or die('Unable to connect. Check your connection parameters.');

$query = 'CREATE DATABASE IF NOT EXISTS diet';
mysqli_query($db, $query) or die(mysqli_error($db));

mysqli_select_db($db, 'diet') or die(mysqli_error($db));

$query = 'CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=MyISAM';
mysqli_query($db, $query) or die(mysqli_error($db));

$query = 'CREATE TABLE IF NOT EXISTS records (
    record_date DATE NOT NULL,
    user_id INT NOT NULL,
    total_calories INT,
    weight DECIMAL(5,2),
    PRIMARY KEY (record_date, user_id),
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=MyISAM';
mysqli_query($db, $query) or die(mysqli_error($db));

$query = 'CREATE TABLE IF NOT EXISTS menu_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    record_date DATE NOT NULL,
    menu VARCHAR(100),
    calories INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=MyISAM';
mysqli_query($db, $query) or die(mysqli_error($db));

if (isset($_SESSION['username'])) {
    // 현재 로그인된 사용자의 ID를 가져옵니다.

    $username = $_SESSION['username'];
    $userQuery = "SELECT id FROM users WHERE username = '$username'";
    $userResult = mysqli_query($db, $userQuery) or die(mysqli_error($db));
    $user = mysqli_fetch_assoc($userResult);
    $user_id = $user['id'];

    if (isset($_POST['weight'])) {
        $date = $_POST['date'];
        $weight = $_POST['weight'];

        // 기존 데이터를 삭제
        $deleteQuery = "DELETE FROM records WHERE user_id = '$user_id' AND record_date = '$date'";
        mysqli_query($db, $deleteQuery) or die(mysqli_error($db));

        $query = "INSERT INTO records (user_id, record_date, weight) 
                  VALUES ('$user_id', '$date', '$weight')";
        mysqli_query($db, $query) or die(mysqli_error($db));
        
        echo "<script>alert('몸무게가 저장되었습니다!'); window.location.href='recordWeight.html';</script>";
    } 
    
    elseif (isset($_POST['totalCalories'])) {
        $date = $_POST['date'];
        $totalCalories = $_POST['totalCalories'];
        $menu = $_POST['menu'];
        $calories = $_POST['calorie'];

        // 기존 데이터 삭제
        $deleteQuery = "DELETE FROM records 
                        WHERE user_id = '$user_id' 
                        AND record_date = '$date'";
        mysqli_query($db, $deleteQuery) or die(mysqli_error($db));
        
        // 총 칼로리 삽입
        $query = "INSERT INTO records (user_id, record_date, total_calories) 
                  VALUES ('$user_id', '$date', '$totalCalories')";
        mysqli_query($db, $query) or die(mysqli_error($db));

        //  기존 데이터 삭제
        $deleteQuery = "DELETE FROM menu_entries 
                        WHERE user_id = '$user_id' 
                        AND record_date = '$date'";
        mysqli_query($db, $deleteQuery) or die(mysqli_error($db));

        // 메뉴삽입
        for ($i = 0; $i < count($menu); $i++) {
            if ($menu[$i] != "" && $calories[$i] != "") {
                $query = "INSERT 
                                INTO menu_entries (user_id, record_date, menu, calories) 
                          VALUES 
                                ('$user_id', '$date', '{$menu[$i]}', '{$calories[$i]}')";
                mysqli_query($db, $query) or die(mysqli_error($db));
            }
        }
        
        echo "<script>alert('식단이 저장되었습니다!'); window.location.href='recordMeal.html';</script>";
    } else{
            echo "<script>alert('로그인이 필요합니다.'); window.location.href='login.php';</script>";
            exit;
        }
    
}
?>
