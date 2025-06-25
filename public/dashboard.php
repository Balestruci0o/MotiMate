<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
}

$userId = $_SESSION['user_id'];
$pageTitle = "Dashboard";

$quote = getRandomQuote();

$goals = $conn->query("SELECT * FROM goals WHERE user_id = $userId ORDER BY deadline ASC LIMIT 3");

$logs = $conn->query("SELECT * FROM daily_logs WHERE user_id = $userId ORDER BY log_date DESC LIMIT 3");

$stats = [
    'total_goals' => $conn->query("SELECT COUNT(*) as count FROM goals WHERE user_id = $userId")->fetch_assoc()['count'],
    'completed_goals' => $conn->query("SELECT COUNT(*) as count FROM goals WHERE user_id = $userId AND is_done = 1")->fetch_assoc()['count'],
    'avg_rating' => $conn->query("SELECT AVG(rating) as avg FROM daily_logs WHERE user_id = $userId")->fetch_assoc()['avg']
];

require_once '../includes/header.php';
?>

<div class="dashboard-grid">
    <div class="card welcome-card">
        <div class="welcome-content">
            <h1 class="welcome-text animate__animated animate__fadeInDown">Welcome back, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
            <p class="welcome-subtext animate__animated animate__fadeInDown animate__delay-1s">"The secret of getting ahead is getting started."</p>
            <a href="daily_log.php" class="btn btn-accent animate__animated animate__fadeInUp animate__delay-2s">
                <i class="fas fa-plus"></i> Add Today's Log
            </a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-bullseye"></i>
            </div>
            <div class="stats-value"><?= $stats['total_goals'] ?></div>
            <div class="stats-label">Total Goals</div>
        </div>
        
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stats-value"><?= $stats['completed_goals'] ?></div>
            <div class="stats-label">Completed Goals</div>
        </div>
        
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stats-value"><?= round($stats['avg_rating'], 1) ?: '0.0' ?></div>
            <div class="stats-label">Avg. Daily Rating</div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-bullseye"></i> Recent Goals</h2>
            <a href="goals.php" class="btn btn-outline">View All</a>
        </div>
        
        <?php if ($goals->num_rows > 0): ?>
            <div class="goals-list">
                <?php while ($goal = $goals->fetch_assoc()): ?>
                    <div class="goal-item">
                        <div class="goal-checkbox">
                            <input type="checkbox" <?= $goal['is_done'] ? 'checked' : '' ?> disabled>
                        </div>
                        <div class="goal-content">
                            <h3 class="goal-title"><?= htmlspecialchars($goal['title']) ?></h3>
                            <div class="goal-meta">
                                <span class="goal-category"><?= ucfirst($goal['category']) ?></span>
                                <span class="goal-deadline"><i class="far fa-calendar-alt"></i> <?= $goal['deadline'] ?></span>
                            </div>
                        </div>
                        <div class="goal-actions">
                            <a href="view_goal.php?id=<?= $goal['id'] ?>" class="btn btn-outline" style="padding: 0.5rem 1rem;">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>You don't have any goals yet. <a href="add_goal.php" class="btn btn-primary" style="display: inline-block; margin-top: 1rem;">
                <i class="fas fa-plus"></i> Add Your First Goal
            </a></p>
        <?php endif; ?>
    </div>

    <div class="card quote-card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-quote-right"></i> Today's Inspiration</h2>
            <a href="quotes.php" class="btn btn-outline">More Quotes</a>
        </div>
        
        <?php if ($quote): ?>
            <blockquote class="quote-text"><?= htmlspecialchars($quote['quote']) ?></blockquote>
            <p class="quote-author">— <?= $quote['author'] ? htmlspecialchars($quote['author']) : 'Unknown' ?></p>
        <?php else: ?>
            <p>No quotes available. <a href="quotes.php">Add some quotes</a> to get inspired!</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>