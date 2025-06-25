<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
}

$userId = $_SESSION['user_id'];
$filter = $_GET['filter'] ?? 'all';
$order = $_GET['order'] ?? 'deadline';

$query = "SELECT * FROM goals WHERE user_id = $userId";

if ($filter === 'completed') {
    $query .= " AND is_done = 1";
} elseif ($filter === 'pending') {
    $query .= " AND is_done = 0";
}

if ($order === 'deadline') {
    $query .= " ORDER BY deadline ASC";
} elseif ($order === 'created') {
    $query .= " ORDER BY created_at DESC";
} elseif ($order === 'category') {
    $query .= " ORDER BY category, deadline";
}

$goals = $conn->query($query);

$pageTitle = "Your Goals";
require_once '../includes/header.php';
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-bullseye"></i> Your Goals</h1>
        <a href="add_goal.php" class="btn btn-accent">
            <i class="fas fa-plus"></i> Add Goal
        </a>
    </div>
    
    <div class="card filters-card mb-4">
        <div class="d-flex flex-wrap align-items-center">
            <span class="mr-3">Filter:</span>
            <div class="btn-group btn-group-toggle mr-4" data-toggle="buttons">
                <label class="btn btn-outline <?= $filter === 'all' ? 'active' : '' ?>">
                    <input type="radio" name="filter" value="all" <?= $filter === 'all' ? 'checked' : '' ?>> All
                </label>
                <label class="btn btn-outline <?= $filter === 'pending' ? 'active' : '' ?>">
                    <input type="radio" name="filter" value="pending" <?= $filter === 'pending' ? 'checked' : '' ?>> Pending
                </label>
                <label class="btn btn-outline <?= $filter === 'completed' ? 'active' : '' ?>">
                    <input type="radio" name="filter" value="completed" <?= $filter === 'completed' ? 'checked' : '' ?>> Completed
                </label>
            </div>
            
            <span class="mr-3">Sort by:</span>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-outline <?= $order === 'deadline' ? 'active' : '' ?>">
                    <input type="radio" name="order" value="deadline" <?= $order === 'deadline' ? 'checked' : '' ?>> Deadline
                </label>
                <label class="btn btn-outline <?= $order === 'created' ? 'active' : '' ?>">
                    <input type="radio" name="order" value="created" <?= $order === 'created' ? 'checked' : '' ?>> Created
                </label>
                <label class="btn btn-outline <?= $order === 'category' ? 'active' : '' ?>">
                    <input type="radio" name="order" value="category" <?= $order === 'category' ? 'checked' : '' ?>> Category
                </label>
            </div>
        </div>
    </div>
    
    <?php if ($goals->num_rows > 0): ?>
        <div class="goals-list">
            <?php while ($goal = $goals->fetch_assoc()): ?>
                <div class="goal-item card">
                    <div class="goal-content">
                        <div class="d-flex align-items-center">
                            <div class="goal-checkbox mr-3">
                                <input type="checkbox" id="goal-<?= $goal['id'] ?>" 
                                    <?= $goal['is_done'] ? 'checked' : '' ?>
                                    data-goal-id="<?= $goal['id'] ?>"
                                    class="goal-status-toggle">
                                <label for="goal-<?= $goal['id'] ?>"></label>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="goal-title <?= $goal['is_done'] ? 'completed' : '' ?>">
                                    <?= htmlspecialchars($goal['title']) ?>
                                </h3>
                                <div class="goal-meta">
                                    <span class="goal-category badge">
                                        <?= ucfirst($goal['category']) ?>
                                    </span>
                                    <span class="goal-deadline text-muted">
                                        <i class="far fa-calendar-alt"></i> <?= $goal['deadline'] ?>
                                    </span>
                                </div>
                                <?php if ($goal['description']): ?>
                                    <p class="goal-description mt-2">
                                        <?= nl2br(htmlspecialchars($goal['description'])) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="goal-actions">
                                <a href="view_goal.php?id=<?= $goal['id'] ?>" class="btn btn-sm btn-outline mr-2">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="edit_goal.php?id=<?= $goal['id'] ?>" class="btn btn-sm btn-outline">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="card empty-state">
            <div class="text-center py-5">
                <i class="fas fa-bullseye fa-3x mb-3" style="color: var(--primary-light);"></i>
                <h3>No goals found</h3>
                <p class="text-muted">You don't have any goals yet. Start by adding your first goal!</p>
                <a href="add_goal.php" class="btn btn-primary mt-3">
                    <i class="fas fa-plus"></i> Add Goal
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.filters-card {
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
}

.btn-group-toggle .btn {
    padding: 0.5rem 1rem;
}

.goal-checkbox {
    position: relative;
}

.goal-checkbox input[type="checkbox"] {
    opacity: 0;
    position: absolute;
    width: 24px;
    height: 24px;
    cursor: pointer;
}

.goal-checkbox label {
    display: inline-block;
    width: 24px;
    height: 24px;
    border: 2px solid var(--primary);
    border-radius: 6px;
    position: relative;
    cursor: pointer;
    transition: var(--hover-transition);
}

.goal-checkbox input[type="checkbox"]:checked + label {
    background-color: var(--primary);
    border-color: var(--primary);
}

.goal-checkbox label::after {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.goal-checkbox input[type="checkbox"]:checked + label::after {
    opacity: 1;
}

.goal-title.completed {
    text-decoration: line-through;
    color: var(--gray);
}

.empty-state {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
}

.badge {
    display: inline-block;
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 50px;
    background-color: rgba(108, 92, 231, 0.1);
    color: var(--primary);
}

.d-flex {
    display: flex;
}

.justify-content-between {
    justify-content: space-between;
}

.align-items-center {
    align-items: center;
}

.flex-wrap {
    flex-wrap: wrap;
}

.mr-3 {
    margin-right: 1rem;
}

.mr-4 {
    margin-right: 1.5rem;
}

.mr-2 {
    margin-right: 0.5rem;
}

.mt-2 {
    margin-top: 0.5rem;
}

.mt-3 {
    margin-top: 1rem;
}

.mb-4 {
    margin-bottom: 1.5rem;
}

.flex-grow-1 {
    flex-grow: 1;
}

.text-muted {
    color: var(--gray);
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.text-center {
    text-align: center;
}

.py-5 {
    padding-top: 3rem;
    padding-bottom: 3rem;
}
</style>

<script>
document.querySelectorAll('.goal-status-toggle').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const goalId = this.dataset.goalId;
        const isDone = this.checked ? 1 : 0;
        
        fetch('toggle_goal.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${goalId}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(() => {
            const goalTitle = this.closest('.goal-content').querySelector('.goal-title');
            if (isDone) {
                goalTitle.classList.add('completed');
                gsap.to(this.closest('.goal-item'), {
                    backgroundColor: 'rgba(0, 184, 148, 0.05)',
                    duration: 0.5
                });
            } else {
                goalTitle.classList.remove('completed');
                gsap.to(this.closest('.goal-item'), {
                    backgroundColor: 'white',
                    duration: 0.5
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.checked = !this.checked;
        });
    });
});

document.querySelectorAll('input[name="filter"], input[name="order"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const params = new URLSearchParams(window.location.search);
        params.set(this.name, this.value);
        window.location.search = params.toString();
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>