<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle : 'MotiMate' ?> - Your Motivation Companion</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary: #6c5ce7;
            --primary-dark: #5649c0;
            --accent: #fd79a8;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container header-container">
            <a href="dashboard.php" class="logo animate__animated animate__fadeIn">
                <i class="fas fa-brain logo-icon"></i> MotiMate
            </a>
            <nav class="nav-links">
                <?php if (isLoggedIn()): ?>
                    <a href="dashboard.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a href="goals.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'goals.php' ? 'active' : '' ?>">
                        <i class="fas fa-bullseye"></i> Goals
                    </a>
                    <a href="daily_log.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'daily_log.php' ? 'active' : '' ?>">
                        <i class="fas fa-calendar-day"></i> Daily Log
                    </a>
                    <a href="quotes.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'quotes.php' ? 'active' : '' ?>">
                        <i class="fas fa-quote-right"></i> Quotes
                    </a>
                    <a href="stats.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'stats.php' ? 'active' : '' ?>">
                        <i class="fas fa-chart-line"></i> Stats
                    </a>
                    <a href="logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                <?php else: ?>
                    <a href="login.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '' ?>">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a href="register.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'register.php' ? 'active' : '' ?>">
                        <i class="fas fa-user-plus"></i> Register
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="main">
        <div class="container">