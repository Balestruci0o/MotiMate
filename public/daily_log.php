<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
}

$userId = $_SESSION['user_id'];
$error = '';
$success = '';

$today = date('Y-m-d');
$existingLog = $conn->query("SELECT * FROM daily_logs WHERE user_id = $userId AND log_date = '$today'")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $logDate = sanitizeInput($_POST['log_date']);
    $rating = (int)$_POST['rating'];
    $comment = sanitizeInput($_POST['comment']);
    
    if ($existingLog) {
        $update = $conn->query("UPDATE daily_logs SET rating = $rating, comment = '$comment' WHERE id = {$existingLog['id']}");
        if ($update) {
            $success = 'Daily log updated successfully!';
        } else {
            $error = 'Failed to update daily log';
        }
    } else {
        $insert = $conn->query("INSERT INTO daily_logs (user_id, log_date, rating, comment) 
                               VALUES ($userId, '$logDate', $rating, '$comment')");
        if ($insert) {
            $success = 'Daily log added successfully!';
            $existingLog = $conn->query("SELECT * FROM daily_logs WHERE user_id = $userId AND log_date = '$logDate'")->fetch_assoc();
        } else {
            $error = 'Failed to add daily log';
        }
    }
}

$pageTitle = "Daily Log";
require_once '../includes/header.php';
?>

<div class="container">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-4">
                <i class="fas fa-calendar-day"></i> 
                <?= $existingLog ? 'Update' : 'Add' ?> Today's Log
                <small class="text-muted"><?= date('F j, Y') ?></small>
            </h1>
            
            <?php if ($error): ?>
                <div class="alert alert-error animate__animated animate__shakeX">
                    <?= $error ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success animate__animated animate__fadeIn">
                    <?= $success ?>
                </div>
            <?php endif; ?>
            
            <form method="post" class="daily-log-form">
                <input type="hidden" name="log_date" value="<?= $today ?>">
                
                <div class="form-group">
                    <label class="form-label">How was your day?</label>
                    <div class="rating-container">
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <div class="rating-item <?= $existingLog && $existingLog['rating'] == $i ? 'active' : '' ?>">
                                <input type="radio" id="rating-<?= $i ?>" name="rating" value="<?= $i ?>" 
                                    <?= $existingLog && $existingLog['rating'] == $i ? 'checked' : '' ?> required>
                                <label for="rating-<?= $i ?>">
                                    <div class="rating-circle"><?= $i ?></div>
                                </label>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="comment" class="form-label">Comments (optional)</label>
                    <textarea id="comment" name="comment" class="form-control" rows="5"><?= $existingLog['comment'] ?? '' ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?= $existingLog ? 'Update' : 'Save' ?> Log
                    </button>
                    <a href="dashboard.php" class="btn btn-outline">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.daily-log-form {
    max-width: 800px;
    margin: 0 auto;
}

.rating-container {
    display: flex;
    justify-content: space-between;
    margin: 1.5rem 0;
    flex-wrap: wrap;
}

.rating-item {
    text-align: center;
    margin: 0.5rem;
    cursor: pointer;
    transition: var(--hover-transition);
}

.rating-item input[type="radio"] {
    display: none;
}

.rating-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-weight: 600;
    color: white;
    background: linear-gradient(135deg, #b2fefa, #0ed2f7);
    transition: var(--hover-transition);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.rating-item:hover .rating-circle {
    transform: scale(1.1);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.rating-item.active .rating-circle {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    box-shadow: 0 8px 20px rgba(108, 92, 231, 0.2);
    transform: scale(1.1);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
}

@media (max-width: 768px) {
    .rating-container {
        justify-content: center;
    }
    
    .rating-item {
        margin: 0.3rem;
    }
    
    .rating-circle {
        width: 40px;
        height: 40px;
        font-size: 0.9rem;
    }
}
</style>

<script>
document.querySelectorAll('.rating-item').forEach(item => {
    item.addEventListener('click', function() {
        document.querySelectorAll('.rating-item').forEach(i => {
            i.classList.remove('active');
        });
        this.classList.add('active');
        
        gsap.from(this.querySelector('.rating-circle'), {
            scale: 0.8,
            duration: 0.3,
            ease: "elastic.out(1, 0.5)"
        });
    });
});

gsap.from(".daily-log-form", {
    opacity: 0,
    y: 20,
    duration: 0.8,
    delay: 0.3
});
</script>

<?php require_once '../includes/footer.php'; ?>