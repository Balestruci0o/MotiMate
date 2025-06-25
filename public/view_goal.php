<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
}

$userId = $_SESSION['user_id'];
$goalId = $_GET['id'] ?? 0;

$goal = $conn->query("SELECT * FROM goals WHERE id = $goalId AND user_id = $userId")->fetch_assoc();

if (!$goal) {
    $_SESSION['error'] = 'Goal not found';
    header("Location: goals.php");
}

$pageTitle = htmlspecialchars($goal['title']);
require_once '../includes/header.php';
?>

<div class="container">
    <div class="card goal-detail-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h1 class="mb-2"><?= htmlspecialchars($goal['title']) ?></h1>
                    <div class="goal-meta">
                        <span class="badge category-badge"><?= ucfirst($goal['category']) ?></span>
                        <span class="text-muted ml-3">
                            <i class="far fa-calendar-alt"></i> <?= date('M j, Y', strtotime($goal['deadline'])) ?>
                        </span>
                        <span class="status-badge <?= $goal['is_done'] ? 'completed' : 'pending' ?> ml-3">
                            <?= $goal['is_done'] ? 'Completed' : 'Pending' ?>
                        </span>
                    </div>
                </div>
                <div class="goal-actions">
                    <a href="edit_goal.php?id=<?= $goal['id'] ?>" class="btn btn-outline mr-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="toggle_goal.php" method="post" style="display: inline;">
                        <input type="hidden" name="id" value="<?= $goal['id'] ?>">
                        <button type="submit" class="btn <?= $goal['is_done'] ? 'btn-secondary' : 'btn-success' ?>">
                            <i class="fas fa-check"></i> <?= $goal['is_done'] ? 'Mark Pending' : 'Mark Completed' ?>
                        </button>
                    </form>
                </div>
            </div>
            
            <?php if ($goal['description']): ?>
                <div class="goal-description">
                    <h3 class="section-title">
                        <i class="fas fa-align-left"></i> Description
                    </h3>
                    <p><?= nl2br(htmlspecialchars($goal['description'])) ?></p>
                </div>
            <?php endif; ?>
            
            <div class="goal-progress mt-5">
                <h3 class="section-title">
                    <i class="fas fa-tasks"></i> Progress
                </h3>
                
                <div class="progress-container">
                    <div class="progress-labels d-flex justify-content-between mb-2">
                        <span>Created: <?= date('M j, Y', strtotime($goal['created_at'])) ?></span>
                        <span>Deadline: <?= date('M j, Y', strtotime($goal['deadline'])) ?></span>
                    </div>
                    
                    <?php
                    $created = strtotime($goal['created_at']);
                    $deadline = strtotime($goal['deadline']);
                    $today = time();
                    $total = $deadline - $created;
                    $passed = $today - $created;
                    
                    $percentage = min(100, max(0, ($passed / $total) * 100));
                    $remaining = ceil(($deadline - $today) / (60 * 60 * 24));
                    ?>
                    
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: <?= $percentage ?>%"></div>
                    </div>
                    
                    <div class="progress-info mt-2">
                        <?php if ($remaining > 0): ?>
                            <span class="text-<?= $remaining < 7 ? 'danger' : 'success' ?>">
                                <i class="fas fa-clock"></i> <?= $remaining ?> days remaining
                            </span>
                        <?php elseif ($remaining == 0): ?>
                            <span class="text-warning">
                                <i class="fas fa-exclamation-circle"></i> Deadline is today!
                            </span>
                        <?php else: ?>
                            <span class="text-danger">
                                <i class="fas fa-exclamation-triangle"></i> Deadline passed <?= abs($remaining) ?> days ago
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <a href="goals.php" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Goals
        </a>
    </div>
</div>

<style>
.goal-detail-card {
    border-radius: 20px;
    overflow: hidden;
}

.category-badge {
    background-color: rgba(108, 92, 231, 0.1);
    color: var(--primary);
    font-size: 0.9rem;
    padding: 0.35rem 0.75rem;
}

.status-badge {
    font-size: 0.9rem;
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
}

.status-badge.completed {
    background-color: rgba(0, 184, 148, 0.1);
    color: var(--success);
}

.status-badge.pending {
    background-color: rgba(253, 203, 110, 0.1);
    color: var(--warning);
}

.section-title {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    color: var(--primary);
    display: flex;
    align-items: center;
}

.section-title i {
    margin-right: 0.75rem;
    color: var(--accent);
}

.goal-description {
    background: rgba(255, 255, 255, 0.7);
    padding: 1.5rem;
    border-radius: 12px;
    margin-top: 2rem;
}

.progress-container {
    background: rgba(255, 255, 255, 0.7);
    padding: 1.5rem;
    border-radius: 12px;
}

.progress-bar-container {
    height: 10px;
    background: rgba(0, 0, 0, 0.05);
    border-radius: 5px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: var(--bg-gradient);
    border-radius: 5px;
    transition: width 1s ease;
}

.ml-3 {
    margin-left: 1rem;
}

.mt-5 {
    margin-top: 3rem;
}

.mt-2 {
    margin-top: 0.5rem;
}

.mt-4 {
    margin-top: 1.5rem;
}

.mb-2 {
    margin-bottom: 0.5rem;
}

.mb-4 {
    margin-bottom: 1.5rem;
}
</style>

<script>
gsap.from(".progress-bar", {
    width: "0%",
    duration: 1.5,
    ease: "power2.out"
});

gsap.from(".goal-detail-card", {
    opacity: 0,
    y: 30,
    duration: 0.8,
    delay: 0.2
});
</script>

<?php require_once '../includes/footer.php'; ?>