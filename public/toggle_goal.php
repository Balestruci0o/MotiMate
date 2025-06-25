<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $goalId = $_POST['id'] ?? 0;
    
    $conn->query("UPDATE goals SET is_done = NOT is_done WHERE id = $goalId AND user_id = $userId");
}

header("Location: goals.php");
?>