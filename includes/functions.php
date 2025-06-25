<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function sanitizeInput($data) {
    global $conn;
    return htmlspecialchars(stripslashes(trim($conn->real_escape_string($data))));
}

function getRandomQuote() {
    global $conn;
    $query = "SELECT * FROM quotes WHERE user_id IS NULL OR user_id = " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0) . " ORDER BY RAND() LIMIT 1";
    $result = $conn->query($query);
    return $result->fetch_assoc();
}
?>